<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')
            ->with(['enrollments.course']);
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by enrollment status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'enrolled') {
                $query->has('enrollments');
            } elseif ($request->status === 'not_enrolled') {
                $query->doesntHave('enrollments');
            }
        }
        
        $clients = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.clients.index', compact('clients'));
    }
    
    public function show(User $client)
    {
        if ($client->role !== 'student') {
            abort(404);
        }
        
        $client->load(['enrollments.course.lessons']);
        
        // OPTIMIZED: Get all completed lessons in ONE query
        $completedLessonIds = $client->lessonProgress()
            ->where('completed', true)
            ->pluck('lesson_id')
            ->toArray();
        
        // OPTIMIZED: Calculate course completion stats
        $completedCourses = 0;
        $inProgressCourses = 0;
        
        foreach ($client->enrollments as $enrollment) {
            $totalLessons = $enrollment->course->lessons->count();
            if ($totalLessons > 0) {
                $courseLessonIds = $enrollment->course->lessons->pluck('id')->toArray();
                $completedLessons = count(array_intersect($completedLessonIds, $courseLessonIds));
                
                if ($completedLessons >= $totalLessons) {
                    $completedCourses++;
                } elseif ($completedLessons > 0) {
                    $inProgressCourses++;
                }
            }
        }
        
        // Calculate statistics
        $stats = [
            'total_enrollments' => $client->enrollments->count(),
            'completed_courses' => $completedCourses,
            'in_progress_courses' => $inProgressCourses,
            'total_spent' => $client->enrollments->sum(function($enrollment) {
                return $enrollment->course->is_free ? 0 : $enrollment->course->price;
            }),
            'join_date' => $client->created_at->format('M d, Y'),
            'last_activity' => $client->updated_at->format('M d, Y H:i')
        ];
        
        return view('admin.clients.show', compact('client', 'stats'));
    }
    
    public function edit(User $client)
    {
        if ($client->role !== 'student') {
            abort(404);
        }
        
        return view('admin.clients.edit', compact('client'));
    }
    
    public function update(Request $request, User $client)
    {
        if ($client->role !== 'student') {
            abort(404);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $client->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        
        $client->update($data);
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'Student updated successfully!');
    }
    
    public function enrollments(User $client)
    {
        if ($client->role !== 'student') {
            abort(404);
        }
        
        $enrollments = $client->enrollments()
            ->with(['course.category', 'course.instructor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.clients.enrollments', compact('client', 'enrollments'));
    }
    
    public function activities(User $client, Request $request)
    {
        if ($client->role !== 'student') {
            abort(404);
        }
        
        $perPage = 10;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $filter = $request->get('filter', 'all');
        
        // OPTIMIZED: Pre-calculate all course progress data in ONE query
        $enrollments = $client->enrollments()
            ->with(['course.lessons'])
            ->get();
        
        // OPTIMIZED: Get ALL completed lessons for this student in ONE query
        $completedLessonIds = $client->lessonProgress()
            ->where('completed', true)
            ->pluck('lesson_id')
            ->toArray();
        
        // OPTIMIZED: Build course progress cache
        $courseProgressCache = [];
        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            $courseId = $course->id;
            $totalLessons = $course->lessons->count();
            
            // Count completed lessons for this course
            $courseLessonIds = $course->lessons->pluck('id')->toArray();
            $completedLessons = count(array_intersect($completedLessonIds, $courseLessonIds));
            
            $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
            
            // Determine course status
            $courseStatus = 'not_started';
            if ($totalLessons > 0) {
                if ($completedLessons >= $totalLessons) {
                    $courseStatus = 'completed';
                } elseif ($completedLessons > 0) {
                    $courseStatus = 'in_progress';
                }
            }
            
            $courseProgressCache[$courseId] = [
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'progress_percentage' => $progressPercentage,
                'course_status' => $courseStatus,
                'course_title' => $course->title
            ];
        }
        
        // OPTIMIZED: Get lesson progress with pre-loaded data
        $lessonProgress = $client->lessonProgress()
            ->with(['lesson.course'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($progress) use ($courseProgressCache) {
                $course = $progress->lesson->course;
                $courseId = $course->id;
                $cached = $courseProgressCache[$courseId] ?? null;
                
                if (!$cached) {
                    return null;
                }
                
                return [
                    'type' => 'lesson_progress',
                    'title' => $progress->completed ? 'Completed Lesson' : 'Started Lesson',
                    'description' => "Lesson: {$progress->lesson->title} in {$course->title}",
                    'date' => $progress->updated_at,
                    'status' => $progress->completed ? 'completed' : 'in_progress',
                    'course' => $cached['course_title'],
                    'course_id' => $courseId,
                    'progress_percentage' => $cached['progress_percentage'],
                    'completed_lessons' => $cached['completed_lessons'],
                    'total_lessons' => $cached['total_lessons'],
                    'course_status' => $cached['course_status']
                ];
            })
            ->filter();
        
        // OPTIMIZED: Get enrollment activities using cached data
        $enrollmentActivities = $enrollments->map(function($enrollment) use ($courseProgressCache) {
            $course = $enrollment->course;
            $courseId = $course->id;
            $cached = $courseProgressCache[$courseId] ?? null;
            
            if (!$cached) {
                return null;
            }
            
            return [
                'type' => 'enrollment',
                'title' => 'Enrolled in Course',
                'description' => "Course: {$course->title}",
                'date' => $enrollment->created_at,
                'status' => 'enrolled',
                'course' => $cached['course_title'],
                'course_id' => $courseId,
                'progress_percentage' => $cached['progress_percentage'],
                'completed_lessons' => $cached['completed_lessons'],
                'total_lessons' => $cached['total_lessons'],
                'course_status' => $cached['course_status']
            ];
        })
        ->filter();
        
        // Merge and deduplicate
        $allActivities = $lessonProgress->merge($enrollmentActivities)
            ->sortByDesc('date')
            ->groupBy('course_id')
            ->map(function($courseActivities) {
                return $courseActivities->first();
            })
            ->values();
        
        // Apply filter
        if ($filter !== 'all') {
            $allActivities = $allActivities->filter(function($activity) use ($filter) {
                return $activity['course_status'] === $filter;
            });
        }
        
        // Manual pagination
        $total = $allActivities->count();
        $activities = $allActivities->slice($offset, $perPage)->values();
        
        // Create pagination data
        $pagination = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'from' => $total > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, $total),
            'has_more_pages' => $page < ceil($total / $perPage),
            'prev_page_url' => $page > 1 ? request()->fullUrlWithQuery(['page' => $page - 1]) : null,
            'next_page_url' => $page < ceil($total / $perPage) ? request()->fullUrlWithQuery(['page' => $page + 1]) : null,
        ];
        
        // OPTIMIZED: Calculate stats from cached data
        $completed = collect($courseProgressCache)->where('course_status', 'completed')->count();
        $inProgress = collect($courseProgressCache)->where('course_status', 'in_progress')->count();
        $notStarted = collect($courseProgressCache)->where('course_status', 'not_started')->count();
        
        $filterStats = [
            'all' => count($courseProgressCache),
            'completed' => $completed,
            'in_progress' => $inProgress,
            'not_started' => $notStarted
        ];
        
        return view('admin.clients.activities', compact('client', 'activities', 'pagination', 'filter', 'filterStats'));
    }
}
