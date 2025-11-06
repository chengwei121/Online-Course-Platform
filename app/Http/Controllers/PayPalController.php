<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\PayPalService;
use App\Services\PaymentOptimizationService;
use App\Services\NotificationService;
use App\Mail\PaymentReceiptMail;
use App\Notifications\StudentEnrolledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PayPalController extends Controller
{
    private $paypalService;
    private $paymentOptimizationService;
    private $notificationService;

    public function __construct(
        PayPalService $paypalService, 
        PaymentOptimizationService $paymentOptimizationService,
        NotificationService $notificationService
    )
    {
        $this->paypalService = $paypalService;
        $this->paymentOptimizationService = $paymentOptimizationService;
        $this->notificationService = $notificationService;
        $this->middleware('auth');
    }

    /**
     * Show payment confirmation page with optimizations
     */
    public function confirm($courseId)
    {
        try {
            // Use optimization service for faster data loading
            $course = $this->paymentOptimizationService->getOptimizedCourseData($courseId);
            
            if (!$course) {
                return redirect()->back()->with('error', 'Course not found.');
            }
            
            $user = Auth::user();

            // Fast enrollment check using optimization service
            $validation = $this->paymentOptimizationService->preValidatePayment($courseId);
            
            if (!$validation['valid']) {
                if ($validation['error'] === 'Already enrolled') {
                    return redirect()->route('client.courses.show', $course->slug)
                        ->with('info', 'You are already enrolled in this course.');
                }
                return redirect()->back()->with('error', $validation['error']);
            }

            // Pre-warm PayPal for faster connection
            $this->paymentOptimizationService->preWarmPayPal();

            return view('client.courses.payment-confirm', compact('course'));

        } catch (\Exception $e) {
            Log::error('Payment Confirmation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Course not found.');
        }
    }

    /**
     * Initiate PayPal payment for a course
     */
    public function createPayment(Request $request, $courseId)
    {
        try {
            // Get only required course data for payment
            $course = Course::select('id', 'title', 'price', 'slug')->findOrFail($courseId);
            $user = Auth::user();

            // Fast enrollment check
            $isEnrolled = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('payment_status', 'completed')
                ->exists();

            if ($isEnrolled) {
                return redirect()->back()->with('error', 'You are already enrolled in this course.');
            }

            // Create URLs for success and cancel
            $successUrl = route('client.paypal.success');
            $cancelUrl = route('client.paypal.cancel');

            // Create PayPal payment
            $payment = $this->paypalService->createPayment(
                $course->price,
                'MYR',
                "Course: {$course->title}",
                $successUrl,
                $cancelUrl,
                $course->id
            );

            // Store payment info in session for later verification
            session([
                'paypal_payment_id' => $payment['payment_id'],
                'course_id' => $course->id,
                'amount' => $course->price
            ]);

            // Redirect to PayPal approval URL
            return redirect($payment['approval_url']);

        } catch (\Exception $e) {
            Log::error('PayPal Payment Creation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to process payment. Please try again.');
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        try {
            $paymentId = $request->get('paymentId');
            $payerId = $request->get('PayerID');

            // Verify payment ID matches session
            if (!$paymentId || $paymentId !== session('paypal_payment_id')) {
                return redirect()->route('client.courses.index')->with('error', 'Invalid payment session.');
            }

            // Execute the payment
            $payment = $this->paypalService->executePayment($paymentId, $payerId);

            if ($this->paypalService->isPaymentApproved($payment)) {
                // Get course and user info from session
                $courseId = session('course_id');
                $expectedAmount = session('amount');
                $user = Auth::user();

                // Verify payment amount matches
                $paidAmount = $this->paypalService->getTransactionAmount($payment);
                
                if (abs($paidAmount - $expectedAmount) > 0.01) {
                    Log::warning("Payment amount mismatch. Expected: {$expectedAmount}, Paid: {$paidAmount}");
                    return redirect()->route('client.courses.index')->with('error', 'Payment amount verification failed.');
                }

                DB::beginTransaction();
                try {
                    // Create or update enrollment
                    $enrollment = Enrollment::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'course_id' => $courseId,
                        ],
                        [
                            'payment_status' => 'completed',
                            'amount_paid' => $paidAmount,
                            'enrolled_at' => now(),
                            'status' => 'in_progress'
                        ]
                    );

                    // Get course information for email
                    $course = Course::find($courseId);
                    
                    if (!$course) {
                        Log::error("Course not found after payment: $courseId");
                        throw new \Exception('Course not found after payment.');
                    }

                    // Send email receipt to student
                    try {
                        Mail::to($user->email)->queue(new PaymentReceiptMail($enrollment, $course, $user, $payment));
                        Log::info("Payment receipt email queued for user: {$user->email}, course: {$course->title}");
                    } catch (\Exception $emailError) {
                        // Log email error but don't fail the payment process
                        Log::error("Failed to send payment receipt email: " . $emailError->getMessage());
                    }

                    // Notify the teacher about new enrollment
                    try {
                        if ($course->teacher && $course->teacher->user) {
                            $teacher = $course->teacher->user;
                            
                            // Create custom notification for teacher
                            $this->notificationService->createNotification(
                                $teacher,
                                'enrollment',
                                'New Student Enrollment',
                                "{$user->name} has enrolled in your course: {$course->title}",
                                null,
                                [
                                    'student_id' => $user->id,
                                    'student_name' => $user->name,
                                    'student_email' => $user->email,
                                    'course_id' => $course->id,
                                    'course_title' => $course->title,
                                    'enrollment_id' => $enrollment->id,
                                    'payment_status' => $enrollment->payment_status,
                                    'amount_paid' => $enrollment->amount_paid,
                                ],
                                route('teacher.courses.show', $course->id),
                                'high'
                            );
                            
                            // Send email to teacher
                            Mail::to($teacher->email)->queue(new \App\Mail\TeacherEnrollmentNotification($user, $course, $enrollment));
                            
                            Log::info("Teacher notified about new enrollment for course: {$course->title}");
                        }
                    } catch (\Exception $notifyError) {
                        // Log notification error but don't fail the payment process
                        Log::error("Failed to notify teacher: " . $notifyError->getMessage());
                    }

                    // Notify admin(s) about new enrollment
                    try {
                        $admins = User::where('role', 'admin')->get();
                        foreach ($admins as $admin) {
                            $instructor = $course->teacher ? $course->teacher->user : null;
                            if ($instructor) {
                                Mail::to($admin->email)->queue(new \App\Mail\AdminEnrollmentNotification($user, $instructor, $course, $enrollment));
                            }
                        }
                        Log::info("Admin(s) notified about new enrollment for course: {$course->title}");
                    } catch (\Exception $notifyError) {
                        Log::error("Failed to notify admin: " . $notifyError->getMessage());
                    }

                    // Notify the student about successful enrollment
                    try {
                        $this->notificationService->createNotification(
                            $user,
                            'enrollment_success',
                            'Successfully Enrolled in Course',
                            "You have successfully enrolled in {$course->title}. Start learning now!",
                            null,
                            [
                                'course_id' => $course->id,
                                'course_title' => $course->title,
                                'enrollment_id' => $enrollment->id,
                                'amount_paid' => $enrollment->amount_paid,
                                'instructor_name' => $course->instructor->name ?? 'Unknown',
                            ],
                            route('client.courses.learn', $course->slug),
                            'normal'
                        );

                        // Send enrollment confirmation email to student
                        Mail::to($user->email)->queue(new \App\Mail\StudentEnrollmentConfirmation($user, $course, $enrollment));
                        
                        Log::info("Student notified about successful enrollment for course: {$course->title}");
                    } catch (\Exception $notifyError) {
                        Log::error("Failed to notify student: " . $notifyError->getMessage());
                    }

                    // Clear session data
                    session()->forget(['paypal_payment_id', 'course_id', 'amount']);

                    DB::commit();
                    
                    return redirect()->route('client.paypal.confirm', $course->id)
                        ->with('payment_success', 'Successfully purchased the course! You can learn the course now.')
                        ->with('course_purchased', true);

                } catch (\Exception $e) {
                    DB::rollback();
                    Log::error('Enrollment creation error: ' . $e->getMessage());
                    return redirect()->route('client.courses.index')->with('error', 'Payment processed but enrollment failed. Please contact support.');
                }
            } else {
                return redirect()->route('client.courses.index')->with('error', 'Payment was not approved.');
            }

        } catch (\Exception $e) {
            Log::error('PayPal Success Handler Error: ' . $e->getMessage());
            return redirect()->route('client.courses.index')->with('error', 'Payment processing failed.');
        }
    }

    /**
     * Handle cancelled payment
     */
    public function cancel(Request $request)
    {
        // Clear session data
        session()->forget(['paypal_payment_id', 'course_id', 'amount']);

        $courseId = session('course_id');
        if ($courseId) {
            return redirect()->route('client.courses.show', $courseId)->with('info', 'Payment was cancelled.');
        }

        return redirect()->route('client.courses.index')->with('info', 'Payment was cancelled.');
    }

    /**
     * Show payment status (optional endpoint for checking payment status)
     */
    public function status(Request $request, $paymentId)
    {
        try {
            $payment = $this->paypalService->getPaymentDetails($paymentId);
            
            return response()->json([
                'status' => $payment['state'],
                'payment_id' => $payment['id'],
                'amount' => $this->paypalService->getTransactionAmount($payment),
                'approved' => $this->paypalService->isPaymentApproved($payment)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch payment status'], 400);
        }
    }

    /**
     * AJAX endpoint for payment preparation
     */
    public function preparePayment(Request $request, $courseId)
    {
        try {
            $validation = $this->paymentOptimizationService->preValidatePayment($courseId);
            
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'error' => $validation['error']
                ], 400);
            }

            // Pre-warm PayPal connection
            $paypalReady = $this->paymentOptimizationService->preWarmPayPal();

            return response()->json([
                'success' => true,
                'paypal_ready' => $paypalReady,
                'course' => [
                    'id' => $validation['course_id'],
                    'title' => $validation['title'],
                    'price' => $validation['price']
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Preparation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to prepare payment'
            ], 500);
        }
    }
}