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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

        // Cache dashboard data for 5 minutes for better performance
        $cacheKey = 'teacher_dashboard_' . $teacher->id;
        $dashboardData = Cache::remember($cacheKey, 300, function () use ($teacher) {
            return $this->getDashboardData($teacher);
        });

        return view('teacher.dashboard', array_merge(
            ['teacher' => $teacher],
            $dashboardData
        ));
    }
    
    /**
     * Get dashboard data efficiently with optimized queries
     */
    private function getDashboardData($teacher)
    {
        // Single query to get course IDs and count
        $courseData = DB::table('courses')
            ->where('teacher_id', $teacher->id)
            ->select('id')
            ->get();
        
        $totalCourses = $courseData->count();
        
        if ($totalCourses === 0) {
            return [
                'courses' => collect(),
                'totalCourses' => 0,
                'totalStudents' => 0,
                'totalAssignments' => 0,
                'recentEnrollments' => collect(),
                'pendingSubmissions' => collect()
            ];
        }
        
        $courseIds = $courseData->pluck('id')->toArray();
        
        // Parallel execution using multiple queries for better performance
        // Get courses with only necessary columns and relationships
        $courses = Course::where('teacher_id', $teacher->id)
            ->select('id', 'title', 'thumbnail', 'status', 'price', 'created_at')
            ->withCount([
                'enrollments' => function($query) {
                    $query->select(DB::raw('count(*)'));
                },
                'lessons' => function($query) {
                    $query->select(DB::raw('count(*)'));
                }
            ])
            ->latest()
            ->limit(5)
            ->get();

        // Optimized student count with single query
        $totalStudents = DB::table('enrollments')
            ->whereIn('course_id', $courseIds)
            ->distinct()
            ->count('user_id');
        
        // Optimized assignment count
        $totalAssignments = DB::table('assignments')
            ->join('lessons', 'assignments.lesson_id', '=', 'lessons.id')
            ->whereIn('lessons.course_id', $courseIds)
            ->count();

        // Get recent enrollments with minimal eager loading
        $recentEnrollments = Enrollment::select('id', 'user_id', 'course_id', 'created_at')
            ->whereIn('course_id', $courseIds)
            ->with([
                'user' => function($query) {
                    $query->select('id', 'name');
                },
                'course' => function($query) {
                    $query->select('id', 'title');
                }
            ])
            ->latest()
            ->limit(5)
            ->get();

        // Optimized pending submissions query
        $pendingSubmissions = DB::table('assignment_submissions')
            ->join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.id')
            ->join('lessons', 'assignments.lesson_id', '=', 'lessons.id')
            ->whereIn('lessons.course_id', $courseIds)
            ->whereNotNull('assignment_submissions.submitted_at')
            ->whereNull('assignment_submissions.score')
            ->select(
                'assignment_submissions.id',
                'assignment_submissions.assignment_id',
                'assignment_submissions.user_id',
                'assignment_submissions.submitted_at as created_at'
            )
            ->orderBy('assignment_submissions.submitted_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($submission) {
                // Lazy load relationships only when needed
                $submission->assignment = Assignment::select('id', 'title')->find($submission->assignment_id);
                $submission->user = DB::table('users')->select('id', 'name')->find($submission->user_id);
                // Convert created_at string to Carbon instance
                $submission->created_at = \Carbon\Carbon::parse($submission->created_at);
                return $submission;
            });

        return compact(
            'courses',
            'totalCourses',
            'totalStudents',
            'totalAssignments',
            'recentEnrollments',
            'pendingSubmissions'
        );
    }
    
    /**
     * Clear dashboard cache (call this when data changes)
     */
    public static function clearCache($teacherId)
    {
        Cache::forget('teacher_dashboard_' . $teacherId);
    }
}
