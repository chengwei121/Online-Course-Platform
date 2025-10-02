<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Services\PayPalService;
use App\Services\PaymentOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    private $paypalService;
    private $paymentOptimizationService;

    public function __construct(PayPalService $paypalService, PaymentOptimizationService $paymentOptimizationService)
    {
        $this->paypalService = $paypalService;
        $this->paymentOptimizationService = $paymentOptimizationService;
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

                    // Clear session data
                    session()->forget(['paypal_payment_id', 'course_id', 'amount']);

                    DB::commit();

                    $course = Course::find($courseId);
                    
                    if (!$course) {
                        Log::error("Course not found after payment: $courseId");
                        return redirect()->route('client.courses.index')->with('error', 'Course not found after payment.');
                    }
                    
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