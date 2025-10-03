<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;

class PaymentReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $enrollment;
    public $course;
    public $student;
    public $payment;
    public $receiptNumber;
    public $paymentDate;
    public $amount;
    public $isTest;

    /**
     * Create a new message instance.
     */
    public function __construct(Enrollment $enrollment, Course $course, User $student, $payment, $isTest = false)
    {
        $this->enrollment = $enrollment;
        $this->course = $course;
        $this->student = $student;
        $this->payment = $payment;
        $this->isTest = $isTest;
        $this->receiptNumber = 'RCP-' . str_pad($enrollment->id ?? rand(1000, 9999), 6, '0', STR_PAD_LEFT);
        $this->paymentDate = $enrollment->enrolled_at ?? now();
        $this->amount = $enrollment->amount_paid ?? $course->price;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isTest 
            ? '[TEST] Payment Receipt - Course Enrollment Confirmation'
            : 'Payment Receipt - Course Enrollment Confirmation';
            
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $subject,
            tags: ['payment-receipt', 'course-enrollment'],
            metadata: [
                'enrollment_id' => $this->enrollment->id,
                'course_id' => $this->course->id,
                'student_id' => $this->student->id,
                'is_test' => $this->isTest,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-receipt',
            with: [
                'enrollment' => $this->enrollment,
                'course' => $this->course,
                'student' => $this->student,
                'receiptNumber' => $this->receiptNumber,
                'paymentDate' => $this->paymentDate,
                'amount' => $this->amount,
                'isTest' => $this->isTest,
                'companyInfo' => [
                    'name' => 'E-learning Platform',
                    'address' => 'Online Education Center, Malaysia',
                    'phone' => '+60 12-567-3550',
                    'email' => config('mail.from.address'),
                    'website' => config('app.url'),
                ],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
