<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\AssignmentSubmission;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the teacher dashboard
     */
    public function index()
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return redirect()->route('login')->with('error', 'Teacher profile not found.');
        }

        // Get teacher's courses
        $courses = Course::where('teacher_id', $teacher->id)
            ->withCount(['enrollments', 'lessons'])
            ->latest()
            ->take(5)
            ->get();

        // Get total stats
        $totalCourses = Course::where('teacher_id', $teacher->id)->count();
        $totalStudents = Enrollment::whereIn('course_id', 
            Course::where('teacher_id', $teacher->id)->pluck('id')
        )->distinct('user_id')->count();
        
        // Get total assignments (assignments are linked to lessons, not directly to courses)
        $totalAssignments = Assignment::whereIn('lesson_id',
            Lesson::whereIn('course_id', Course::where('teacher_id', $teacher->id)->pluck('id'))->pluck('id')
        )->count();

        // Get recent enrollments
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->whereIn('course_id', Course::where('teacher_id', $teacher->id)->pluck('id'))
            ->latest()
            ->take(5)
            ->get();

        // Get pending assignment submissions (submissions that are submitted but not yet graded)
        $pendingSubmissions = AssignmentSubmission::with(['assignment', 'user'])
            ->whereIn('assignment_id', 
                Assignment::whereIn('lesson_id', 
                    Lesson::whereIn('course_id', 
                        Course::where('teacher_id', $teacher->id)->pluck('id')
                    )->pluck('id')
                )->pluck('id')
            )
            ->whereNotNull('submitted_at')
            ->whereNull('score') // Not yet graded
            ->latest('submitted_at')
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact(
            'teacher',
            'courses',
            'totalCourses',
            'totalStudents',
            'totalAssignments',
            'recentEnrollments',
            'pendingSubmissions'
        ));
    }
}
