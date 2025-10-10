<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{
    /**
     * Display a listing of students for the authenticated teacher (OPTIMIZED)
     */
    public function index(Request $request)
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', 'Teacher profile not found.');
        }

        // Cache course IDs for this teacher (5 minutes)
        $cacheKey = 'teacher_courses_' . $teacher->id;
        $teacherCourses = Cache::remember($cacheKey, 300, function () use ($teacher) {
            return DB::table('courses')
                ->where('teacher_id', $teacher->id)
                ->pluck('id')
                ->toArray();
        });

        if (empty($teacherCourses)) {
            return view('teacher.students.index', [
                'students' => collect()->paginate(15),
                'courses' => collect(),
                'stats' => [
                    'total_students' => 0,
                    'active_enrollments' => 0,
                    'completed_enrollments' => 0,
                    'total_courses' => 0
                ]
            ]);
        }

        // Build optimized query with only necessary columns
        $query = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.created_at',
                'students.avatar',
                DB::raw('COUNT(DISTINCT enrollments.id) as enrollments_count')
            )
            ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->whereIn('enrollments.course_id', $teacherCourses)
            ->where('users.role', 'student')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.created_at', 'students.avatar');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('course_id')) {
            $courseId = $request->get('course_id');
            $query->where('enrollments.course_id', $courseId);
        }

        if ($request->filled('status')) {
            $status = $request->get('status');
            $query->where('enrollments.status', $status);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        switch ($sortBy) {
            case 'email':
                $query->orderBy('users.email', $sortOrder);
                break;
            case 'joined':
                $query->orderBy('users.created_at', $sortOrder);
                break;
            case 'name':
            default:
                $query->orderBy('users.name', $sortOrder);
                break;
        }

        // Paginate with custom paginator
        $perPage = 15;
        $page = $request->get('page', 1);
        $total = $query->count(DB::raw('DISTINCT users.id'));
        
        $students = $query->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get()
            ->map(function($student) use ($teacherCourses) {
                // Lazy load enrollments only for current page
                $student->enrollments = DB::table('enrollments')
                    ->whereIn('course_id', $teacherCourses)
                    ->where('user_id', $student->id)
                    ->get();
                return $student;
            });

        // Convert to Laravel paginator
        $students = new \Illuminate\Pagination\LengthAwarePaginator(
            $students,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get courses for dropdown (cached)
        $coursesCacheKey = 'teacher_courses_dropdown_' . $teacher->id;
        $courses = Cache::remember($coursesCacheKey, 600, function () use ($teacher) {
            return DB::table('courses')
                ->where('teacher_id', $teacher->id)
                ->select('id', 'title')
                ->orderBy('title')
                ->get();
        });

        // Optimized statistics (cached for 2 minutes)
        $statsCacheKey = 'teacher_students_stats_' . $teacher->id;
        $stats = Cache::remember($statsCacheKey, 120, function () use ($teacherCourses) {
            return [
                'total_students' => DB::table('users')
                    ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
                    ->whereIn('enrollments.course_id', $teacherCourses)
                    ->where('users.role', 'student')
                    ->distinct('users.id')
                    ->count('users.id'),
                'active_enrollments' => DB::table('enrollments')
                    ->whereIn('course_id', $teacherCourses)
                    ->where('status', 'active')
                    ->count(),
                'completed_enrollments' => DB::table('enrollments')
                    ->whereIn('course_id', $teacherCourses)
                    ->where('status', 'completed')
                    ->count(),
                'total_courses' => count($teacherCourses)
            ];
        });

        return view('teacher.students.index', compact('students', 'courses', 'stats'));
    }

    /**
     * Show detailed view of a specific student
     */
    public function show($id, Request $request)
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', 'Teacher profile not found.');
        }

        // Get teacher's courses
        $teacherCourses = Course::where('teacher_id', $teacher->id)->pluck('id');

        // Find the student and ensure they're enrolled in teacher's courses
        $student = User::select('users.*', 'students.phone', 'students.bio', 'students.date_of_birth', 'students.address', 'students.avatar')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('users.id', $id)
            ->where('users.role', 'student')
            ->whereExists(function($query) use ($teacherCourses) {
                $query->select(DB::raw(1))
                    ->from('enrollments')
                    ->whereRaw('enrollments.user_id = users.id')
                    ->whereIn('enrollments.course_id', $teacherCourses);
            })
            ->first();

        if (!$student) {
            return redirect()->route('teacher.students.index')
                ->with('error', 'Student not found or not enrolled in your courses.');
        }

        // Get student's enrollments in teacher's courses
        $enrollments = Enrollment::where('user_id', $student->id)
            ->whereIn('course_id', $teacherCourses)
            ->with(['course' => function($query) {
                $query->select('id', 'title', 'description', 'thumbnail', 'price');
            }])
            ->get();

        // Get student's progress and assignments (you can expand this based on your needs)
        $progress = [];
        foreach ($enrollments as $enrollment) {
            $courseId = $enrollment->course_id;
            
            // Calculate progress percentage (this is a basic calculation)
            $totalLessons = DB::table('lessons')->where('course_id', $courseId)->count();
            $completedLessons = DB::table('lesson_progress')
                ->where('user_id', $student->id)
                ->whereIn('lesson_id', function($query) use ($courseId) {
                    $query->select('id')->from('lessons')->where('course_id', $courseId);
                })
                ->where('completed', true)
                ->count();
            
            $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0;
            
            $progress[$courseId] = [
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'percentage' => $progressPercentage
            ];
        }

        return view('teacher.students.show', compact('student', 'enrollments', 'progress'));
    }

    /**
     * Send message to student (placeholder for future messaging system)
     */
    public function sendMessage(Request $request, $id)
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return response()->json(['error' => 'Teacher profile not found.'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Placeholder for messaging system
        // You can implement actual messaging functionality here
        
        return response()->json([
            'success' => true,
            'message' => 'Message functionality will be implemented soon.'
        ]);
    }
}