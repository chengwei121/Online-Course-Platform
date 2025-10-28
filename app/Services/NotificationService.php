<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Create a notification for a single user
     */
    public function createNotification(
        User $user,
        string $type,
        string $title,
        string $message,
        ?User $sender = null,
        array $data = [],
        ?string $actionUrl = null,
        string $priority = 'normal'
    ): Notification {
        return Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'user_id' => $user->id,
            'sender_id' => $sender?->id,
            'action_url' => $actionUrl,
            'priority' => $priority,
        ]);
    }

    /**
     * Create notifications for multiple users
     */
    public function createBulkNotifications(
        Collection $users,
        string $type,
        string $title,
        string $message,
        ?User $sender = null,
        array $data = [],
        ?string $actionUrl = null,
        string $priority = 'normal'
    ): Collection {
        $notifications = collect();

        foreach ($users as $user) {
            $notification = $this->createNotification(
                $user, $type, $title, $message, $sender, $data, $actionUrl, $priority
            );
            $notifications->push($notification);
        }

        return $notifications;
    }

    /**
     * Notify all students in a course
     */
    public function notifyCourseStudents(
        int $courseId,
        string $type,
        string $title,
        string $message,
        ?User $sender = null,
        array $data = [],
        ?string $actionUrl = null,
        string $priority = 'normal'
    ) {
        $students = User::whereHas('enrollments', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();

        return $this->createBulkNotifications(
            $students, $type, $title, $message, $sender, 
            array_merge($data, ['course_id' => $courseId]), 
            $actionUrl, $priority
        );
    }

    /**
     * Notify all students
     */
    public function notifyAllStudents(
        string $type,
        string $title,
        string $message,
        ?User $sender = null,
        array $data = [],
        ?string $actionUrl = null,
        string $priority = 'normal'
    ) {
        $students = User::where('role', 'student')->get();

        return $this->createBulkNotifications(
            $students, $type, $title, $message, $sender, $data, $actionUrl, $priority
        );
    }

    /**
     * Create course update notification
     */
    public function notifyCourseUpdate(int $courseId, string $updateMessage, ?User $sender = null)
    {
        return $this->notifyCourseStudents(
            $courseId,
            'course_update',
            'Course Updated',
            $updateMessage,
            $sender,
            ['course_id' => $courseId],
            route('client.courses.show', $courseId)
        );
    }

    /**
     * Create new assignment notification
     */
    public function notifyNewAssignment(int $courseId, int $assignmentId, string $assignmentTitle, ?User $sender = null)
    {
        return $this->notifyCourseStudents(
            $courseId,
            'assignment',
            'New Assignment Available',
            "A new assignment '{$assignmentTitle}' has been posted.",
            $sender,
            ['course_id' => $courseId, 'assignment_id' => $assignmentId],
            route('client.assignments.show', $assignmentId),
            'high'
        );
    }

    /**
     * Create enrollment confirmation notification
     */
    public function notifyEnrollment(User $student, int $courseId, string $courseTitle)
    {
        return $this->createNotification(
            $student,
            'enrollment',
            'Course Enrollment Confirmed',
            "You have successfully enrolled in '{$courseTitle}'. Start learning now!",
            null,
            ['course_id' => $courseId],
            route('client.courses.show', $courseId)
        );
    }

    /**
     * Create general announcement
     */
    public function createAnnouncement(
        string $title,
        string $message,
        ?User $sender = null,
        string $targetAudience = 'all_students'
    ) {
        if ($targetAudience === 'all_students') {
            return $this->notifyAllStudents(
                'announcement',
                $title,
                $message,
                $sender,
                [],
                null,
                'normal'
            );
        }
        
        // Can be extended for other target audiences
    }

    /**
     * Get unread notifications count for a user
     */
    public function getUnreadCount(User $user): int
    {
        return $user->customNotifications()->unread()->count();
    }

    /**
     * Get recent notifications for a user
     */
    public function getRecentNotifications(User $user, int $limit = 10): Collection
    {
        return $user->customNotifications()
            ->with('sender')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, User $user): bool
    {
        $notification = $user->customNotifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        return false;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user): int
    {
        return $user->customNotifications()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }
}