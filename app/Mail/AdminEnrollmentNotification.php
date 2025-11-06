<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminEnrollmentNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $student;
    public $instructor;
    public $course;
    public $enrollment;

    /**
     * Create a new message instance.
     */
    public function __construct(User $student, User $instructor, Course $course, Enrollment $enrollment)
    {
        $this->student = $student;
        $this->instructor = $instructor;
        $this->course = $course;
        $this->enrollment = $enrollment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Enrollment: ' . $this->course->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-enrollment-notification',
            with: [
                'studentName' => $this->student->name,
                'studentEmail' => $this->student->email,
                'courseTitle' => $this->course->title,
                'instructorName' => $this->instructor->name,
                'instructorEmail' => $this->instructor->email,
                'enrollmentDate' => $this->enrollment->created_at->format('F d, Y h:i A'),
                'amountPaid' => 'RM' . number_format($this->enrollment->amount_paid, 2),
                'paymentStatus' => ucfirst($this->enrollment->payment_status),
                'enrollmentUrl' => route('admin.courses.show', $this->course->id),
            ]
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
