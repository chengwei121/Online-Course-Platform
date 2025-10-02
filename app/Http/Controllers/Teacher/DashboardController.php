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

        // Use a simpler, faster approach for initial dashboard load
        return $this->getDashboardData($teacher);
    }
    
    /**
     * Get dashboard data efficiently
     */
    private function getDashboardData($teacher)
    {
        // Get basic stats first (fastest queries)
        $totalCourses = Course::where('teacher_id', $teacher->id)->count();
        
        if ($totalCourses === 0) {
            // If no courses, return empty dashboard quickly
            return view('teacher.dashboard', [
                'teacher' => $teacher,
                'courses' => collect(),
                'totalCourses' => 0,
                'totalStudents' => 0,
                'totalAssignments' => 0,
                'recentEnrollments' => collect(),
                'pendingSubmissions' => collect()
            ]);
        }
        
        // Get course IDs once
        $courseIds = Course::where('teacher_id', $teacher->id)->pluck('id');
        
        // Get courses with minimal data
        $courses = Course::where('teacher_id', $teacher->id)
            ->select('id', 'title', 'thumbnail', 'status', 'created_at')
            ->withCount('enrollments')
            ->latest()
            ->take(5)
            ->get();

        // Get student count efficiently
        $totalStudents = Enrollment::whereIn('course_id', $courseIds)
            ->distinct()
            ->count('user_id');
        
        // Get assignment count using join for better performance
        $totalAssignments = Assignment::join('lessons', 'assignments.lesson_id', '=', 'lessons.id')
            ->whereIn('lessons.course_id', $courseIds)
            ->count();

        // Get recent enrollments with minimal data
        $recentEnrollments = Enrollment::with(['user:id,name', 'course:id,title'])
            ->whereIn('course_id', $courseIds)
            ->select('id', 'user_id', 'course_id', 'created_at')
            ->latest()
            ->take(5)
            ->get();

        // Get pending submissions efficiently
        $pendingSubmissions = AssignmentSubmission::join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.id')
            ->join('lessons', 'assignments.lesson_id', '=', 'lessons.id')
            ->whereIn('lessons.course_id', $courseIds)
            ->whereNotNull('assignment_submissions.submitted_at')
            ->whereNull('assignment_submissions.score')
            ->select('assignment_submissions.id', 'assignment_submissions.assignment_id', 'assignment_submissions.user_id', 'assignment_submissions.submitted_at')
            ->with(['assignment:id,title', 'user:id,name'])
            ->latest('assignment_submissions.submitted_at')
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
