<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeacherAssignmentSubmissionNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $student;
    public $assignment;
    public $submission;
    public $course;

    /**
     * Create a new message instance.
     */
    public function __construct(User $student, Assignment $assignment, AssignmentSubmission $submission, Course $course)
    {
        $this->student = $student;
        $this->assignment = $assignment;
        $this->submission = $submission;
        $this->course = $course;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Assignment Submission: ' . $this->assignment->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.teacher-assignment-submission-notification',
            with: [
                'studentName' => $this->student->name,
                'studentEmail' => $this->student->email,
                'assignmentTitle' => $this->assignment->title,
                'courseTitle' => $this->course->title,
                'submissionDate' => $this->submission->submitted_at->format('F d, Y h:i A'),
                'dueDate' => $this->assignment->due_date ? $this->assignment->due_date->format('F d, Y h:i A') : 'No due date',
                'isLate' => $this->assignment->due_date && $this->submission->submitted_at > $this->assignment->due_date,
                'submissionUrl' => route('teacher.submissions.grade', $this->submission->id),
                'hasFile' => !empty($this->submission->submission_file),
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
