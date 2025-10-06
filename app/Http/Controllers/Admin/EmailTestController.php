<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentReceiptMail;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailTestController extends Controller
{
    /**
     * Show the email testing page
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'role')->take(10)->get();
        $courses = Course::select('id', 'title', 'price')->take(10)->get();
        
        return view('admin.emails.test', compact('users', 'courses'));
    }

    /**
     * Preview the email template without touching the database
     */
    public function preview(Request $request)
    {
        $userId = $request->get('user_id', User::first()->id);
        $courseId = $request->get('course_id', Course::first()->id);
        
        $user = User::findOrFail($userId);
        $course = Course::findOrFail($courseId);
        
        // Create a MOCK enrollment object without saving to database
        $enrollment = new Enrollment([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_status' => 'completed',
            'amount_paid' => $course->price,
            'enrolled_at' => now(),
            'status' => 'in_progress'
        ]);
        
        // Set a fake ID for testing display
        $enrollment->id = 999999;
        $enrollment->exists = true; // Trick Laravel into thinking this is a saved model
        
        // Create mock payment data
        $payment = (object) [
            'id' => 'TEST-PREVIEW-' . time(),
            'transactions' => [
                (object) [
                    'amount' => (object) [
                        'total' => $course->price,
                        'currency' => 'MYR'
                    ]
                ]
            ]
        ];
        
        $mailable = new PaymentReceiptMail($enrollment, $course, $user, $payment, true);
        $rendered = $mailable->render();
        
        return $rendered;
    }

    /**
     * Send a test email without affecting real database data
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'test_email' => 'required|email'
        ]);
        
        try {
            $user = User::findOrFail($request->user_id);
            $course = Course::findOrFail($request->course_id);
            
            // Create a MOCK enrollment object without saving to database
            $enrollment = new Enrollment([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'payment_status' => 'completed',
                'amount_paid' => $course->price,
                'enrolled_at' => now(),
                'status' => 'in_progress'
            ]);
            
            // Set a fake ID for testing display
            $enrollment->id = 888888;
            $enrollment->exists = true; // Trick Laravel into thinking this is a saved model
            
            // Create mock payment data
            $payment = (object) [
                'id' => 'TEST-EMAIL-' . time(),
                'transactions' => [
                    (object) [
                        'amount' => (object) [
                            'total' => $course->price,
                            'currency' => 'MYR'
                        ]
                    ]
                ]
            ];
            
            // Send test email without queue to avoid serialization issues
            Mail::to($request->test_email)->sendNow(new PaymentReceiptMail($enrollment, $course, $user, $payment, true));
            
            Log::info('Test email sent', [
                'test_email' => $request->test_email,
                'user' => $user->name,
                'course' => $course->title,
                'admin' => Auth::user()->name ?? 'System'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Test email sent successfully to {$request->test_email}"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send test email', [
                'error' => $e->getMessage(),
                'test_email' => $request->test_email,
                'admin' => Auth::user()->name ?? 'System'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }
}