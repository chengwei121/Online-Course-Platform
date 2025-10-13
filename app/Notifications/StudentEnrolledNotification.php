<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentEnrolledNotification extends Notification
{
    use Queueable;

    protected $student;
    protected $course;
    protected $enrollment;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $student, Course $course, Enrollment $enrollment)
    {
        $this->student = $student;
        $this->course = $course;
        $this->enrollment = $enrollment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Student Enrolled: ' . $this->course->title)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('A new student has enrolled in your course.')
                    ->line('**Course:** ' . $this->course->title)
                    ->line('**Student:** ' . $this->student->name)
                    ->line('**Student Email:** ' . $this->student->email)
                    ->line('**Enrollment Date:** ' . $this->enrollment->created_at->format('F d, Y h:i A'))
                    ->line('**Payment Status:** ' . ucfirst($this->enrollment->payment_status))
                    ->action('View Course', url('/teacher/courses/' . $this->course->id))
                    ->line('Thank you for teaching with us!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'student_enrolled',
            'student_id' => $this->student->id,
            'student_name' => $this->student->name,
            'student_email' => $this->student->email,
            'course_id' => $this->course->id,
            'course_title' => $this->course->title,
            'enrollment_id' => $this->enrollment->id,
            'payment_status' => $this->enrollment->payment_status,
            'amount_paid' => $this->enrollment->amount_paid,
            'enrolled_at' => $this->enrollment->created_at->toDateTimeString(),
            'message' => $this->student->name . ' has enrolled in your course: ' . $this->course->title
        ];
    }
}
