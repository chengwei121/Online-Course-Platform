<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    /**
     * Display a listing of lessons for a specific course
     */
    public function index(Course $course)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        $lessons = $course->lessons()
            ->withCount('assignments')
            ->orderBy('order')
            ->get();

        // Return JSON if it's an AJAX request
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'lessons' => $lessons->map(function($lesson) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'description' => $lesson->description,
                        'duration' => $lesson->duration,
                        'order' => $lesson->order,
                        'video_url' => $lesson->video_url,
                        'assignments_count' => $lesson->assignments_count,
                        'created_at' => $lesson->created_at,
                        'updated_at' => $lesson->updated_at,
                    ];
                })
            ]);
        }

        return view('teacher.lessons.index', compact('course', 'lessons'));
    }

    /**
     * Show the form for creating a new lesson
     */
    public function create(Course $course)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Get the next order number
        $nextOrder = $course->lessons()->max('order') + 1;

        return view('teacher.lessons.create', compact('course', 'nextOrder'));
    }

    /**
     * Store a newly created lesson
     */
    public function store(Request $request, Course $course)
    {
        // Increase PHP limits for video uploads at runtime
        ini_set('upload_max_filesize', '500M');
        ini_set('post_max_size', '500M');
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');
        
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this course.'
                ], 403);
            }
            abort(403, 'Unauthorized access to this course.');
        }

        // Check for upload errors first
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            if ($video->getError() !== UPLOAD_ERR_OK) {
                $errorMessage = $this->getUploadErrorMessage($video->getError());
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'error_code' => $video->getError(),
                        'suggestion' => 'Please use the Video URL option instead for large files.'
                    ], 413);
                }
                return back()->withErrors(['video' => $errorMessage])->withInput();
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv|max:500000', // 500MB max
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:1', // Duration in minutes
            'order' => 'required|integer|min:1',
            'content' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
        ]);

        $videoUrl = null;

        // Handle video upload
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_' . Str::slug($request->title) . '.' . $video->getClientOriginalExtension();
            $videoPath = $video->storeAs('videos/courses/' . $course->id, $videoName, 'public');
            // Store just the path relative to storage/app/public, not the full URL
            $videoUrl = $videoPath;
        } elseif ($request->filled('video_url')) {
            $videoUrl = $request->video_url;
        }

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $videoUrl,
            'duration' => $request->duration ?: '0', // Default to '0' if null or empty
            'order' => $request->order,
            'content' => $request->content ?: '',
            'learning_objectives' => $request->learning_objectives ?: '',
        ]);

        // Return JSON response for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lesson created successfully!',
                'lesson' => $lesson,
                'redirect' => route('teacher.courses.lessons.index', $course)
            ]);
        }

        return redirect()
            ->route('teacher.courses.lessons.index', $course)
            ->with('success', 'Lesson created successfully!');
    }

    /**
     * Display the specified lesson
     */
    public function show(Course $course, Lesson $lesson)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        $assignments = $lesson->assignments()->get();

        return view('teacher.lessons.show', compact('course', 'lesson', 'assignments'));
    }

    /**
     * Show the form for editing the specified lesson
     */
    public function edit(Course $course, Lesson $lesson)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        return view('teacher.lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Update the specified lesson
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        // Increase PHP limits for video uploads at runtime
        ini_set('upload_max_filesize', '500M');
        ini_set('post_max_size', '500M');
        ini_set('max_execution_time', 600); // 10 minutes for large uploads
        ini_set('memory_limit', '1024M'); // 1GB for processing large files
        
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        // Check for upload errors first
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            if ($video->getError() !== UPLOAD_ERR_OK) {
                $errorMessage = $this->getUploadErrorMessage($video->getError());
                return back()->withErrors(['video' => $errorMessage])->withInput();
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv|max:500000',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:1', // Duration in minutes
            'order' => 'required|integer|min:1',
            'content' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
        ]);

        $videoUrl = $lesson->video_url;

        // Handle video upload
        if ($request->hasFile('video')) {
            // Delete old video if it exists
            if ($lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
                // Handle both old format (with /storage/) and new format (relative path)
                $oldVideoPath = str_replace('/storage/', '', $lesson->video_url);
                Storage::disk('public')->delete($oldVideoPath);
            }

            $video = $request->file('video');
            $videoName = time() . '_' . Str::slug($request->title) . '.' . $video->getClientOriginalExtension();
            $videoPath = $video->storeAs('videos/courses/' . $course->id, $videoName, 'public');
            // Store just the path relative to storage/app/public, not the full URL
            $videoUrl = $videoPath;
        } elseif ($request->filled('video_url')) {
            $videoUrl = $request->video_url;
        }

        $lesson->update([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $videoUrl,
            'duration' => $request->duration ?: '0', // Default to '0' if null or empty
            'order' => $request->order,
            'content' => $request->content ?: '',
            'learning_objectives' => $request->learning_objectives ?: '',
        ]);

        return redirect()
            ->route('teacher.courses.lessons.show', [$course, $lesson])
            ->with('success', 'Lesson updated successfully!');
    }

    /**
     * Remove the specified lesson
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        // Delete video file if it exists
        if ($lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
            $videoPath = str_replace('/storage/', '', $lesson->video_url);
            Storage::disk('public')->delete($videoPath);
        }

        // Delete all assignments associated with this lesson
        $lesson->assignments()->delete();

        $lesson->delete();

        return redirect()
            ->route('teacher.courses.lessons.index', $course)
            ->with('success', 'Lesson deleted successfully!');
    }

    /**
     * Reorder lessons
     */
    public function reorder(Request $request, Course $course)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        $request->validate([
            'lesson_ids' => 'required|array',
            'lesson_ids.*' => 'exists:lessons,id'
        ]);

        foreach ($request->lesson_ids as $index => $lessonId) {
            Lesson::where('id', $lessonId)
                ->where('course_id', $course->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get user-friendly upload error message
     */
    private function getUploadErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini. Maximum allowed size is ' . ini_get('upload_max_filesize') . '.';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded. Please try again.';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder for file upload.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk.';
            case UPLOAD_ERR_EXTENSION:
                return 'A PHP extension stopped the file upload.';
            default:
                return 'Unknown upload error occurred. Please try with a smaller file or contact support.';
        }
    }
}