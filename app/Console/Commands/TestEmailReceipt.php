<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\PaymentReceiptMail;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TestEmailReceipt extends Command
{
    protected $signature = 'test:email-receipt {user_id} {course_id}';
    protected $description = 'Test the payment receipt email system';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $courseId = $this->argument('course_id');

        // Get the user and course
        $user = User::find($userId);
        $course = Course::find($courseId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return 1;
        }

        if (!$course) {
            $this->error("Course with ID {$courseId} not found.");
            return 1;
        }

        // Create a test enrollment (don't save to database)
        $enrollment = new Enrollment([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_status' => 'completed',
            'amount_paid' => $course->price,
            'enrolled_at' => now(),
            'status' => 'in_progress'
        ]);

        // Set the ID and relationships for the email
        $enrollment->id = 999; // Fake ID for testing
        $enrollment->user = $user;
        $enrollment->course = $course;

        // Create a mock payment object
        $payment = (object) [
            'id' => 'TEST-' . time(),
            'transactions' => [
                (object) [
                    'amount' => (object) [
                        'total' => $course->price,
                        'currency' => 'MYR'
                    ]
                ]
            ],
            'payer' => (object) [
                'payment_method' => 'paypal',
                'payer_info' => (object) [
                    'email' => $user->email,
                    'first_name' => explode(' ', $user->name)[0] ?? $user->name,
                    'last_name' => explode(' ', $user->name)[1] ?? ''
                ]
            ]
        ];

        try {
            // Send the test email
            Mail::to($user->email)->send(new PaymentReceiptMail($enrollment, $course, $user, $payment));
            
            $this->info("✓ Test email sent successfully!");
            $this->info("Email sent to: {$user->email}");
            $this->info("Course: {$course->title}");
            $this->info("Amount: RM " . number_format($course->price, 2));
            
            if (config('mail.mailer') === 'log') {
                $this->info("Check storage/logs/laravel.log for the email content.");
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
            return 1;
        }
    }
}