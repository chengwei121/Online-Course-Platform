<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Services\NotificationService;
use App\Notifications\StudentEnrolledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EnrollmentController extends Controller
{
    private $notificationService;

    /**
     * Create a new controller instance.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->middleware('auth');
    }

    /**
     * Store a new enrollment.
     */
    public function store(Request $request, Course $course)
    {
        // Check if user is already enrolled
        $existingEnrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->route('client.courses.show', $course->slug)
                ->with('error', 'You are already enrolled in this course.');
        }

        // Only allow free courses to be enrolled directly
        if (!$course->is_free) {
            return redirect()->route('client.courses.show', $course->slug)
                ->with('error', 'This is a paid course. Please use PayPal to complete your payment.');
        }

        // Create enrollment for free course
        $enrollment = Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'payment_status' => 'completed', // Free courses are automatically completed
            'amount_paid' => 0, // Free courses cost nothing
            'enrolled_at' => now(),
            'status' => 'in_progress'
        ]);

        // Notify the teacher about new enrollment
        if ($course->teacher && $course->teacher->user) {
            $teacher = $course->teacher->user;
            $student = Auth::user();
            
            // Create custom notification for teacher
            $this->notificationService->createNotification(
                $teacher,
                'enrollment',
                'New Student Enrollment',
                "{$student->name} has enrolled in your free course: {$course->title}",
                null,
                [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'student_email' => $student->email,
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'enrollment_id' => $enrollment->id,
                    'payment_status' => 'completed',
                    'amount_paid' => 0,
                ],
                route('teacher.courses.show', $course->id),
                'high'
            );
            
            // Send email to teacher
            Mail::to($teacher->email)->queue(new \App\Mail\TeacherEnrollmentNotification($student, $course, $enrollment));
        }

        // Notify the student about successful enrollment
        $student = Auth::user();
        $this->notificationService->createNotification(
            $student,
            'enrollment_success',
            'Successfully Enrolled in Course',
            "You have successfully enrolled in {$course->title}. Start learning now!",
            null,
            [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'enrollment_id' => $enrollment->id,
                'amount_paid' => 0,
                'instructor_name' => $course->instructor->name ?? 'Unknown',
            ],
            route('client.courses.learn', $course->slug),
            'normal'
        );

        return redirect()->route('client.courses.learn', $course->slug)
            ->with('success', 'Successfully enrolled in the free course. Start learning now!');
    }

    /**
     * Display a paginated list of enrollments.
     */
    public function index()
    {
        // Get enrollments with course data (only completed payment enrollments)
        $enrollments = Enrollment::with(['course.category', 'course.instructor', 'course.lessons'])
            ->where('user_id', Auth::id())
            ->where('payment_status', 'completed') // Only show successful enrollments
            ->latest('enrolled_at')
            ->paginate(6);

        // Get all completed enrollments for stats calculation
        $allEnrollments = Enrollment::with(['course.lessons'])
            ->where('user_id', Auth::id())
            ->where('payment_status', 'completed')
            ->get();

        // Calculate proper statistics based on lesson progress
        $totalEnrollments = $allEnrollments->count();
        $completedCount = 0;
        $inProgressCount = 0;
        $notStartedCount = 0;

        foreach ($allEnrollments as $enrollment) {
            $totalLessons = $enrollment->course->lessons->count();
            if ($totalLessons > 0) {
                $completedLessons = \App\Models\LessonProgress::where('user_id', Auth::id())
                    ->whereIn('lesson_id', $enrollment->course->lessons->pluck('id'))
                    ->where('completed', true)
                    ->count();
                
                $progressPercentage = round(($completedLessons / $totalLessons) * 100);
                
                if ($progressPercentage == 100) {
                    $completedCount++;
                } elseif ($progressPercentage > 0) {
                    $inProgressCount++;
                } else {
                    $notStartedCount++;
                }
            } else {
                $notStartedCount++;
            }
        }

        $stats = (object) [
            'total' => $totalEnrollments,
            'completed' => $completedCount,
            'in_progress' => $inProgressCount,
            'not_started' => $notStartedCount
        ];

        return view('client.enrollments.index', compact('enrollments', 'stats'));
    }
}