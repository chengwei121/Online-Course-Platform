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

class StudentEnrollmentConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $student;
    public $course;
    public $enrollment;

    /**
     * Create a new message instance.
     */
    public function __construct(User $student, Course $course, Enrollment $enrollment)
    {
        $this->student = $student;
        $this->course = $course;
        $this->enrollment = $enrollment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Enrollment Confirmed: ' . $this->course->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.student-enrollment-confirmation',
            with: [
                'studentName' => $this->student->name,
                'courseTitle' => $this->course->title,
                'instructorName' => $this->course->instructor->name ?? 'Unknown',
                'enrollmentDate' => $this->enrollment->created_at->format('F d, Y h:i A'),
                'amountPaid' => 'RM' . number_format($this->enrollment->amount_paid, 2),
                'courseUrl' => route('client.courses.learn', $this->course->slug),
                'courseLessons' => $this->course->lessons_count ?? $this->course->lessons()->count(),
                'courseDuration' => $this->course->learning_hours ?? 'Not specified',
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
