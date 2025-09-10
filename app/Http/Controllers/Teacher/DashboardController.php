<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\AssignmentSubmission;
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
            return redirect()->route('teacher.login')->with('error', 'Teacher profile not found.');
        }

        // Get teacher's courses
        $courses = Course::where('instructor_id', $teacher->id)
            ->withCount(['enrollments', 'lessons'])
            ->latest()
            ->take(5)
            ->get();

        // Get total stats
        $totalCourses = Course::where('instructor_id', $teacher->id)->count();
        $totalStudents = Enrollment::whereIn('course_id', 
            Course::where('instructor_id', $teacher->id)->pluck('id')
        )->distinct('user_id')->count();
        
        $totalAssignments = Assignment::whereIn('course_id',
            Course::where('instructor_id', $teacher->id)->pluck('id')
        )->count();

        // Get recent enrollments
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->whereIn('course_id', Course::where('instructor_id', $teacher->id)->pluck('id'))
            ->latest()
            ->take(5)
            ->get();

        // Get pending assignment submissions
        $pendingSubmissions = AssignmentSubmission::with(['assignment', 'user'])
            ->whereIn('assignment_id', 
                Assignment::whereIn('course_id', 
                    Course::where('instructor_id', $teacher->id)->pluck('id')
                )->pluck('id')
            )
            ->where('status', 'submitted')
            ->latest()
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
