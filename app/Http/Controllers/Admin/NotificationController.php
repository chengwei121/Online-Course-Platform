<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * Show create announcement form
     */
    public function create()
    {
        $courses = Course::select('id', 'title')->orderBy('title')->get();
        return view('admin.notifications.create', compact('courses'));
    }

    /**
     * Create and send announcement
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_audience' => 'required|in:all_students,course_students',
            'course_id' => 'required_if:target_audience,course_students|exists:courses,id',
            'priority' => 'required|in:low,normal,high'
        ]);

        $sender = Auth::user();

        if ($request->target_audience === 'all_students') {
            $notifications = $this->notificationService->notifyAllStudents(
                'announcement',
                $request->title,
                $request->message,
                $sender,
                [],
                null,
                $request->priority
            );
        } else {
            $notifications = $this->notificationService->notifyCourseStudents(
                $request->course_id,
                'announcement',
                $request->title,
                $request->message,
                $sender,
                ['course_id' => $request->course_id],
                route('client.courses.show', Course::find($request->course_id)->slug),
                $request->priority
            );
        }

        return redirect()->back()->with('success', 
            "Announcement sent to {$notifications->count()} students successfully!"
        );
    }

    /**
     * Quick test notification (for development)
     */
    public function sendTestNotification()
    {
        $students = User::where('role', 'student')->get();
        
        if ($students->count() === 0) {
            return redirect()->back()->with('error', 'No students found to send test notification.');
        }

        $notifications = $this->notificationService->createBulkNotifications(
            $students,
            'system',
            'Welcome to the Notification System! 🎉',
            'This is a test notification to demonstrate the new notification system. You can now receive updates about your courses, assignments, and announcements.',
            Auth::user(),
            [],
            route('client.notifications.index'),
            'normal'
        );

        return redirect()->back()->with('success', 
            "Test notification sent to {$notifications->count()} students!"
        );
    }
}
