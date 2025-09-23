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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()
            ->with(['category', 'instructor'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // Apply filters with support for multiple values
        if ($request->filled('category')) {
            $categories = is_array($request->category) ? $request->category : [$request->category];
            $query->whereIn('category_id', $categories);
        }

        if ($request->filled('level')) {
            $levels = is_array($request->level) ? $request->level : [$request->level];
            $query->whereIn('level', $levels);
        }

        if ($request->filled('duration')) {
            $durations = is_array($request->duration) ? $request->duration : [$request->duration];
            $query->where(function($q) use ($durations) {
                foreach ($durations as $duration) {
                    switch ($duration) {
                        case 'short':
                            $q->orWhere('duration', '<=', 3);
                            break;
                        case 'medium':
                            $q->orWhereBetween('duration', [3, 6]);
                            break;
                        case 'long':
                            $q->orWhere('duration', '>', 6);
                            break;
                    }
                }
            });
        }

        if ($request->filled('rating')) {
            $ratings = is_array($request->rating) ? $request->rating : [$request->rating];
            $minRating = min($ratings); // Use the lowest rating selected
            $query->having('reviews_avg_rating', '>=', $minRating);
        }

        if ($request->filled('price_type')) {
            $priceTypes = is_array($request->price_type) ? $request->price_type : [$request->price_type];
            $query->where(function($q) use ($priceTypes) {
                foreach ($priceTypes as $priceType) {
                    if ($priceType === 'free') {
                        $q->orWhere('is_free', true);
                    } elseif ($priceType === 'premium') {
                        $q->orWhere('is_free', false);
                    }
                }
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $query->latest()->paginate(12);
        
        // Add thumbnail URL accessor
        $courses->each(function ($course) {
            if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
                $course->thumbnail_url = asset('storage/' . $course->thumbnail);
            } else {
                $course->thumbnail_url = asset('images/course-placeholder.jpg');
            }
        });

        $categories = Category::withCount('courses')->get();

        if ($request->ajax()) {
            if ($request->filled('partial')) {
                return view('client.courses._grid', compact('courses'))->render();
            }
            return view('client.courses._grid', compact('courses'));
        }

        return view('client.courses.index', compact('courses', 'categories'));
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
        $course = Course::with(['instructor', 'lessons'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Load instructor's course count and total students
        if ($course->instructor) {
            $course->instructor->courses_count = $course->instructor->courses()->count();
            $course->instructor->students_count = $course->instructor->courses()
                ->withCount('enrollments')
                ->get()
                ->sum('enrollments_count');
        }

        $reviews = $course->reviews()
            ->with('user')
            ->latest()
            ->paginate(5);

        /** @var User|null $user */
        $user = Auth::user();
        $isEnrolled = false;
        $hasCompletedCourse = false;
        $existingReview = null;
        $canReview = false;
        
        if ($user) {
            // Check if user is enrolled
            $enrollment = $user->enrollments()
                ->where('course_id', $course->id)
                ->where('payment_status', 'completed')
                ->first();
            
            $isEnrolled = $enrollment !== null;
            
            if ($isEnrolled) {
                // Check if user has completed the course
                $totalLessons = $course->lessons()->count();
                $completedLessons = LessonProgress::where('user_id', $user->id)
                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                    ->where('completed', true)
                    ->count();
                
                $hasCompletedCourse = $totalLessons > 0 && $completedLessons >= $totalLessons;
                
                // Check if user has already reviewed this course
                $existingReview = CourseReview::where('course_id', $course->id)
                    ->where('user_id', $user->id)
                    ->first();
                
                // User can review if they completed the course and haven't reviewed yet
                $canReview = $hasCompletedCourse && !$existingReview;
            }
        }

        return view('client.courses.show', compact(
            'course', 
            'reviews', 
            'isEnrolled', 
            'hasCompletedCourse', 
            'existingReview', 
            'canReview'
        ));
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

        return view('client.courses.learn', compact('course', 'lesson', 'progress', 'submissions', 'isCourseCompleted', 'hasReviewed'));
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
                // For completion, we'll be more flexible about the requirements
                // Check if they've made reasonable progress (at least 60 seconds or 80% of reported progress)
                $hasMinimumProgress = $progress->video_progress >= 60; // At least 1 minute
                
                Log::info('Checking completion requirements', [
                    'user_id' => $user->id,
                    'lesson_id' => $lesson->id,
                    'current_progress' => $progress->video_progress,
                    'has_minimum_progress' => $hasMinimumProgress,
                    'lesson_duration' => $lesson->duration
                ]);

                // Allow completion if they have minimum progress
                if ($hasMinimumProgress) {
                    $progress->completed = true;
                    $progress->completed_at = now();
                    $progress->save();

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
                            'completed_at' => $progress->completed_at
                        ]
                    ]);
                } else {
                    Log::warning('Insufficient progress for completion', [
                        'user_id' => $user->id,
                        'lesson_id' => $lesson->id,
                        'current_progress' => $progress->video_progress,
                        'required_minimum' => 60
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'You must watch at least 1 minute of the video before marking it as complete.'
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