<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Course;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\CourseReview;
use App\Services\CourseOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    protected $courseOptimizationService;

    public function __construct(CourseOptimizationService $courseOptimizationService)
    {
        $this->courseOptimizationService = $courseOptimizationService;
    }

    public function index(Request $request)
    {
        try {
            // Get filters from request
            $filters = $request->only(['category', 'level', 'duration', 'rating', 'price_type', 'search']);
            
            // Log request for debugging
            Log::info('Course index request', [
                'filters' => $filters,
                'page' => $request->get('page', 1),
                'is_ajax' => $request->ajax()
            ]);
            
            // Get cached categories
            $categories = $this->courseOptimizationService->getCachedCategories();
            
            // Get optimized courses
            $courses = $this->courseOptimizationService->getOptimizedCourses($filters, 9);
            
            // Optimize thumbnails
            $courses->setCollection($this->courseOptimizationService->optimizeThumbnails($courses->getCollection()));

            if ($request->ajax()) {
                $response = [
                    'html' => view('client.courses._grid', compact('courses'))->render(),
                    'pagination' => $courses->appends($request->all())->links()->render(),
                    'has_more' => $courses->hasMorePages(),
                    'next_page' => $courses->nextPageUrl(),
                    'current_page' => $courses->currentPage(),
                    'total' => $courses->total(),
                    'total_pages' => $courses->lastPage(),
                    'per_page' => $courses->perPage()
                ];
                
                Log::info('AJAX response data', [
                    'current_page' => $response['current_page'],
                    'total_pages' => $response['total_pages'],
                    'total' => $response['total'],
                    'has_more' => $response['has_more']
                ]);
                
                return response()->json($response);
            }

            return view('client.courses.index', compact('courses', 'categories'));
            
        } catch (\Exception $e) {
            Log::error('Error loading courses: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Failed to load courses. Please try again.',
                    'html' => '<div class="col-span-full text-center p-8 text-red-500">Error loading courses. Please refresh the page.</div>'
                ], 500);
            }
            
            return back()->with('error', 'Failed to load courses. Please try again.');
        }
    }

    /**
     * Get optimized thumbnail URL with fallback
     */
    private function getOptimizedThumbnailUrl($thumbnail)
    {
        if ($thumbnail && Storage::disk('public')->exists($thumbnail)) {
            return asset('storage/' . $thumbnail);
        }
        return asset('images/course-placeholder.jpg');
    }

    /**
     * Get trending courses based on enrollment count and ratings
     */
    private function getTrendingCourses()
    {
        return Course::with(['category', 'instructor'])
            ->where('status', 'published')
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->orderBy('average_rating', 'desc')
            ->take(5)
            ->get();
    }

    public function show(string $slug): View
    {
        // Use optimization service for course details
        $course = $this->courseOptimizationService->getCourseWithDetails($slug);
        
        if (!$course) {
            abort(404);
        }

        // Optimize reviews query with pagination
        $reviews = $course->reviews()
            ->with('user:id,name')
            ->select('id', 'course_id', 'user_id', 'rating', 'comment', 'created_at')
            ->latest()
            ->paginate(10);

        /** @var User|null $user */
        $user = Auth::user();
        $isEnrolled = false;
        $hasCompletedCourse = false;
        $existingReview = null;
        $canReview = false;
        
        if ($user) {
            // Optimized enrollment check with single query
            $enrollment = $user->enrollments()
                ->where('course_id', $course->id)
                ->where('payment_status', 'completed')
                ->first();
            
            $isEnrolled = $enrollment !== null;
            
            if ($isEnrolled) {
                // Optimized: Get counts in single query instead of two separate queries
                $lessonIds = $course->lessons->pluck('id')->toArray();
                $totalLessons = count($lessonIds);
                
                if ($totalLessons > 0) {
                    // Single query for completed lessons count
                    $completedLessons = LessonProgress::where('user_id', $user->id)
                        ->whereIn('lesson_id', $lessonIds)
                        ->where('completed', true)
                        ->count();
                    
                    $hasCompletedCourse = $completedLessons >= $totalLessons;
                    
                    // Single query for existing review
                    $existingReview = CourseReview::select('id', 'rating', 'comment', 'created_at')
                        ->where('course_id', $course->id)
                        ->where('user_id', $user->id)
                        ->first();
                    
                    // User can review if they completed the course and haven't reviewed yet
                    $canReview = $hasCompletedCourse && !$existingReview;
                }
            }
        }

        // Use show.blade.php as main course show page
        $viewName = view()->exists('client.courses.show') ? 'client.courses.show' : 'client.courses.show_optimized';
        
        return view($viewName, compact(
            'course', 
            'reviews', 
            'isEnrolled', 
            'hasCompletedCourse', 
            'existingReview', 
            'canReview'
        ))->with('enrolled', $isEnrolled);
    }

    /**
     * Get course reviews via API for lazy loading
     */
    public function getReviews(Course $course)
    {
        $reviews = $course->reviews()
            ->with('user:id,name')
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'reviews' => $reviews->map(function($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at,
                    'user' => [
                        'name' => $review->user->name
                    ]
                ];
            })
        ]);
    }

    public function learn(Course $course, ?Lesson $lesson = null)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Load the instructor relationship if not already loaded
        if (!$course->relationLoaded('instructor')) {
            $course->load('instructor');
        }
        
        if (!$user->enrollments()->where('course_id', $course->id)->exists()) {
            return redirect()->route('client.courses.show', $course->slug)
                ->with('error', 'You must be enrolled in this course to access the lessons.');
        }

        if (!$lesson) {
            $lesson = $course->lessons()->first();
            if (!$lesson) {
                return redirect()->route('client.courses.show', $course->slug)
                    ->with('error', 'This course has no lessons yet.');
            }
        }

        $progress = $user->lessonProgress()
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->get();

        // Check if course is completed (all lessons completed)
        $totalLessons = $course->lessons->count();
        $completedLessons = $progress->where('completed', true)->count();
        $isCourseCompleted = $totalLessons > 0 && $completedLessons === $totalLessons;

        // Check if user has already reviewed this course
        $hasReviewed = $user->courseReviews()->where('course_id', $course->id)->exists();

        $submissions = collect();
        if (!$course->is_free) {
            $submissions = $user->assignmentSubmissions()
                ->whereIn('assignment_id', $lesson->assignments->pluck('id'))
                ->get();
        }

        // Use the optimized learning view
        return view('client.courses.learn_optimized', compact('course', 'lesson', 'progress', 'submissions', 'isCourseCompleted', 'hasReviewed'));
    }

    public function uploadVideo(Request $request, Course $course, Lesson $lesson)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,webm|max:102400', // 100MB max
        ]);

        $path = $request->file('video')->store('course-videos', 'public');
        
        $lesson->update([
            'video_url' => Storage::url($path)
        ]);

        return back()->with('success', 'Video uploaded successfully.');
    }

    public function updateProgress(Request $request, Course $course, Lesson $lesson): JsonResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            
            // Log the incoming request data for debugging
            Log::info('Updating progress for lesson', [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'course_id' => $course->id,
                'video_progress' => $request->input('video_progress'),
                'completed' => $request->input('completed')
            ]);
            
            // Check if the user is enrolled in the course
            $enrollment = $user->enrollments()->where('course_id', $course->id)->first();
            if (!$enrollment) {
                Log::warning('User not enrolled in course', [
                    'user_id' => $user->id,
                    'course_id' => $course->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'You must be enrolled in this course to update progress.'
                ], 403);
            }

            // Get current progress
            $currentProgress = LessonProgress::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->first();

            // Check skip limit (120 seconds)
            $requestedProgress = $request->input('video_progress', 0);
            $maxSkipAhead = 120; // Maximum seconds allowed to skip ahead (2 minutes)
            
            if ($currentProgress) {
                $skipDistance = $requestedProgress - $currentProgress->video_progress;
                
                if ($skipDistance > $maxSkipAhead) {
                    Log::warning('Skip limit exceeded', [
                        'user_id' => $user->id,
                        'lesson_id' => $lesson->id,
                        'current_progress' => $currentProgress->video_progress,
                        'requested_progress' => $requestedProgress,
                        'skip_distance' => $skipDistance
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'You can only skip up to 30 seconds ahead in the video.',
                        'data' => [
                            'video_progress' => $currentProgress->video_progress,
                            'completed' => $currentProgress->completed,
                            'completed_at' => $currentProgress->completed_at
                        ]
                    ], 400);
                }
            }

            // Get or create progress record
            $progress = LessonProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'lesson_id' => $lesson->id
                ],
                [
                    'video_progress' => $requestedProgress
                ]
            );

            // If marking as complete
            if ($request->boolean('completed')) {
                // For completion, require 100% of video watched (entire lesson)
                $lessonDurationSeconds = $lesson->duration * 60; // Convert minutes to seconds
                $minimumRequired = $lessonDurationSeconds > 0 ? $lessonDurationSeconds * 0.95 : 60; // 95% for buffer
                $hasMinimumProgress = $progress->video_progress >= $minimumRequired;
                
                Log::info('Checking completion requirements', [
                    'user_id' => $user->id,
                    'lesson_id' => $lesson->id,
                    'current_progress' => $progress->video_progress,
                    'lesson_duration_seconds' => $lessonDurationSeconds,
                    'minimum_required' => $minimumRequired,
                    'has_minimum_progress' => $hasMinimumProgress
                ]);

                // Allow video completion if they watched 95%+
                if ($hasMinimumProgress) {
                    $progress->completed = true;
                    $progress->completed_at = now();
                    $progress->save();

                    // Check if lesson has assignments
                    $hasAssignments = $lesson->assignments()->count() > 0;
                    
                    if ($hasAssignments) {
                        // Check if all assignments are submitted and graded/approved
                        $assignmentIds = $lesson->assignments()->pluck('id');
                        $allSubmissionsGraded = \App\Models\AssignmentSubmission::where('user_id', $user->id)
                            ->whereIn('assignment_id', $assignmentIds)
                            ->where('status', 'graded')
                            ->count() === $assignmentIds->count();
                        
                        if ($allSubmissionsGraded) {
                            // All assignments graded - mark course as completed
                            $enrollment->course_status = 'completed';
                            $enrollment->completed_at = now();
                            $enrollment->save();
                            
                            Log::info('Course marked as completed', [
                                'user_id' => $user->id,
                                'course_id' => $course->id,
                                'lesson_id' => $lesson->id
                            ]);
                        } else {
                            // Assignments exist but not all graded - mark as pending approval
                            $enrollment->course_status = 'pending_approval';
                            $enrollment->save();
                            
                            Log::info('Course status set to pending approval', [
                                'user_id' => $user->id,
                                'course_id' => $course->id
                            ]);
                        }
                    } else {
                        // No assignments - course can be completed immediately
                        $enrollment->course_status = 'completed';
                        $enrollment->completed_at = now();
                        $enrollment->save();
                        
                        Log::info('Course marked as completed (no assignments)', [
                            'user_id' => $user->id,
                            'course_id' => $course->id,
                            'lesson_id' => $lesson->id
                        ]);
                    }

                    Log::info('Lesson marked as complete', [
                        'user_id' => $user->id,
                        'lesson_id' => $lesson->id,
                        'progress' => $progress->video_progress
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Lesson marked as complete successfully.',
                        'data' => [
                            'video_progress' => $progress->video_progress,
                            'completed' => true,
                            'completed_at' => $progress->completed_at,
                            'has_assignments' => $hasAssignments,
                            'course_status' => $enrollment->course_status
                        ]
                    ]);
                } else {
                    $percentageWatched = $lessonDurationSeconds > 0 
                        ? round(($progress->video_progress / $lessonDurationSeconds) * 100, 1)
                        : 0;
                    
                    Log::warning('Insufficient progress for completion', [
                        'user_id' => $user->id,
                        'lesson_id' => $lesson->id,
                        'current_progress' => $progress->video_progress,
                        'required_minimum' => $minimumRequired,
                        'percentage_watched' => $percentageWatched
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => "You must watch 100% of the video before marking it as complete. You've watched {$percentageWatched}%."
                    ], 400);
                }
            }

            // Regular progress update
            return response()->json([
                'success' => true,
                'message' => 'Progress updated successfully',
                'data' => [
                    'video_progress' => $progress->video_progress,
                    'completed' => $progress->completed,
                    'completed_at' => $progress->completed_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating lesson progress: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
                'lesson_id' => $lesson->id,
                'course_id' => $course->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update progress. Please try again.'
            ], 500);
        }
    }

    public function submitAssignment(Request $request, Course $course, Lesson $lesson, Assignment $assignment)
    {
        $request->validate([
            'submission_text' => 'required|string',
            'submission_file' => 'nullable|file|max:10240' // 10MB max
        ]);

        $submission = new AssignmentSubmission([
            'user_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'submission_text' => $request->submission_text,
            'submitted_at' => now()
        ]);

        if ($request->hasFile('submission_file')) {
            $path = $request->file('submission_file')->store('assignment-submissions', 'public');
            $submission->submission_file = Storage::url($path);
        }

        $submission->save();

        return back()->with('success', 'Assignment submitted successfully.');
    }

    public function gradeSubmission(Request $request, Course $course, Lesson $lesson, Assignment $assignment, AssignmentSubmission $submission)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:' . $assignment->points,
            'feedback' => 'required|string'
        ]);

        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback
        ]);

        return back()->with('success', 'Submission graded successfully.');
    }

    /**
     * Store a course review
     */
    public function storeReview(StoreReviewRequest $request, Course $course)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Verify user is enrolled in the course
        $enrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->where('payment_status', 'completed')
            ->first();
            
        if (!$enrollment) {
            return redirect()->back()
                ->with('error', 'You must be enrolled in this course to leave a review.');
        }
        
        // Check if user has completed the course
        $totalLessons = $course->lessons()->count();
        $completedLessons = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->where('completed', true)
            ->count();
            
        $hasCompletedCourse = $totalLessons > 0 && $completedLessons >= $totalLessons;
        
        if (!$hasCompletedCourse) {
            return redirect()->back()
                ->with('error', 'You must complete all lessons before you can review this course.');
        }
        
        // Check if user has already reviewed this course
        $existingReview = CourseReview::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->first();
            
        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'You have already reviewed this course.');
        }
        
        // Create the review
        $review = CourseReview::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'rating' => $request->validated()['rating'],
            'comment' => $request->validated()['comment']
        ]);
        
        // Update course average rating and total ratings
        $this->updateCourseRating($course);
        
        Log::info('Course review created', [
            'course_id' => $course->id,
            'user_id' => $user->id,
            'rating' => $review->rating,
            'review_id' => $review->id
        ]);
        
        return redirect()->back()
            ->with('success', 'Thank you for your review! Your feedback helps other students make informed decisions.');
    }
    
    /**
     * Update course average rating and total ratings count
     */
    private function updateCourseRating(Course $course)
    {
        $reviews = CourseReview::where('course_id', $course->id)->get();
        
        $totalRatings = $reviews->count();
        $averageRating = $totalRatings > 0 ? $reviews->avg('rating') : 0;
        
        $course->update([
            'average_rating' => round($averageRating, 2),
            'total_ratings' => $totalRatings
        ]);
    }
}