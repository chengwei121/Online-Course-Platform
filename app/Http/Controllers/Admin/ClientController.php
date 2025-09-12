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
        
        $client->load(['enrollments.course.category']);
        
        // Calculate statistics
        $stats = [
            'total_enrollments' => $client->enrollments->count(),
            'completed_courses' => $this->getCompletedCoursesCount($client),
            'in_progress_courses' => $this->getInProgressCoursesCount($client),
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
        $filter = $request->get('filter', 'all'); // all, completed, in_progress, not_started
        
        // Get lesson progress activities with completion status
        $lessonProgress = $client->lessonProgress()
            ->with(['lesson.course'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($progress) use ($client) {
                $course = $progress->lesson->course;
                $totalLessons = $course->lessons->count();
                $completedLessons = $client->lessonProgress()
                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                    ->where('completed', true)
                    ->count();
                $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                
                // More precise course status logic
                $courseStatus = 'not_started';
                if ($totalLessons > 0) {
                    if ($completedLessons >= $totalLessons) {
                        $courseStatus = 'completed';
                    } elseif ($completedLessons > 0) {
                        $courseStatus = 'in_progress';
                    }
                }
                
                return [
                    'type' => 'lesson_progress',
                    'title' => $progress->completed ? 'Completed Lesson' : 'Started Lesson',
                    'description' => "Lesson: {$progress->lesson->title} in {$progress->lesson->course->title}",
                    'date' => $progress->updated_at,
                    'status' => $progress->completed ? 'completed' : 'in_progress',
                    'course' => $progress->lesson->course->title,
                    'course_id' => $course->id,
                    'progress_percentage' => $progressPercentage,
                    'completed_lessons' => $completedLessons,
                    'total_lessons' => $totalLessons,
                    'course_status' => $courseStatus
                ];
            });
        
        // Get enrollment activities with completion status
        $enrollmentActivities = $client->enrollments()
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($enrollment) use ($client) {
                $course = $enrollment->course;
                $totalLessons = $course->lessons->count();
                $completedLessons = $client->lessonProgress()
                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                    ->where('completed', true)
                    ->count();
                $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                
                // More precise course status logic
                $courseStatus = 'not_started';
                if ($totalLessons > 0) {
                    if ($completedLessons >= $totalLessons) {
                        $courseStatus = 'completed';
                    } elseif ($completedLessons > 0) {
                        $courseStatus = 'in_progress';
                    }
                }
                
                return [
                    'type' => 'enrollment',
                    'title' => 'Enrolled in Course',
                    'description' => "Course: {$enrollment->course->title}",
                    'date' => $enrollment->created_at,
                    'status' => 'enrolled',
                    'course' => $enrollment->course->title,
                    'course_id' => $course->id,
                    'progress_percentage' => $progressPercentage,
                    'completed_lessons' => $completedLessons,
                    'total_lessons' => $totalLessons,
                    'course_status' => $courseStatus
                ];
            });
        
        // Merge all activities and remove duplicates by course_id to ensure accurate filtering
        $allActivities = $lessonProgress->merge($enrollmentActivities)
            ->sortByDesc('date')
            ->groupBy('course_id')
            ->map(function($courseActivities) {
                // For each course, take the most recent activity
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
        
        // Get filter statistics using the same logic as the activities filtering
        // This ensures the filter counts match the actual filtered results
        $allActivitiesForStats = $lessonProgress->merge($enrollmentActivities)
            ->groupBy('course_id')
            ->map(function($courseActivities) {
                return $courseActivities->first();
            })
            ->values();
        
        $completed = $allActivitiesForStats->where('course_status', 'completed')->count();
        $inProgress = $allActivitiesForStats->where('course_status', 'in_progress')->count(); 
        $notStarted = $allActivitiesForStats->where('course_status', 'not_started')->count();
        
        // Keep the detailed debug info from enrollments for verification
        $allEnrollments = $client->enrollments()->with(['course.lessons'])->get();
        $debugInfo = [];
        
        foreach ($allEnrollments as $enrollment) {
            $course = $enrollment->course;
            $totalLessons = $course->lessons->count();
            
            $completedLessons = $client->lessonProgress()
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->where('completed', true)
                ->count();
            
            $status = 'not_started';
            if ($totalLessons > 0) {
                if ($completedLessons >= $totalLessons) {
                    $status = 'completed';
                } elseif ($completedLessons > 0) {
                    $status = 'in_progress';
                }
            }
            
            $debugInfo[] = [
                'course' => $course->title,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'status' => $status,
                'progress_percentage' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0
            ];
        }
        
        $filterStats = [
            'all' => $allActivitiesForStats->count(),
            'completed' => $completed,
            'in_progress' => $inProgress,
            'not_started' => $notStarted,
            'debug' => $debugInfo // Add debug info for verification
        ];
        
        return view('admin.clients.activities', compact('client', 'activities', 'pagination', 'filter', 'filterStats'));
    }
    
    private function getCompletedCoursesCount(User $client)
    {
        $completedCount = 0;
        
        foreach ($client->enrollments as $enrollment) {
            $course = $enrollment->course;
            $totalLessons = $course->lessons->count();
            
            if ($totalLessons > 0) {
                $completedLessons = $client->lessonProgress()
                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                    ->where('completed', true)
                    ->count();
                
                if ($completedLessons >= $totalLessons) {
                    $completedCount++;
                }
            }
        }
        
        return $completedCount;
    }
    
    private function getInProgressCoursesCount(User $client)
    {
        $inProgressCount = 0;
        
        foreach ($client->enrollments as $enrollment) {
            $course = $enrollment->course;
            $totalLessons = $course->lessons->count();
            
            if ($totalLessons > 0) {
                $completedLessons = $client->lessonProgress()
                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                    ->where('completed', true)
                    ->count();
                
                if ($completedLessons > 0 && $completedLessons < $totalLessons) {
                    $inProgressCount++;
                }
            }
        }
        
        return $inProgressCount;
    }
}
