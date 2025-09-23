<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
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
        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'payment_status' => 'completed', // Free courses are automatically completed
            'amount_paid' => 0, // Free courses cost nothing
            'enrolled_at' => now(),
            'status' => 'in_progress'
        ]);

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