<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function show(Lesson $lesson)
    {
        // Check if user is enrolled in the course
        $user = Auth::user();
        $course = $lesson->course;
        
        if (!$user->enrollments()->where('course_id', $course->id)->exists()) {
            return redirect()->route('client.courses.show', $course)
                           ->with('error', 'You must be enrolled in this course to view lessons.');
        }

        return view('client.lessons.show', compact('lesson'));
    }

    public function uploadVideo(Request $request, Lesson $lesson)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,ogg,webm|max:102400' // 100MB max
        ]);

        if ($request->hasFile('video')) {
            // Delete old video if exists
            if ($lesson->video_url && Storage::exists($lesson->video_url)) {
                Storage::delete($lesson->video_url);
            }

            // Store new video
            $path = $request->file('video')->store('lessons/videos', 'public');
            $lesson->update(['video_url' => $path]);

            return back()->with('success', 'Video uploaded successfully.');
        }

        return back()->with('error', 'No video file uploaded.');
    }

    public function updateProgress(Request $request, Lesson $lesson)
    {
        $request->validate([
            'completed' => 'required|boolean',
            'watched_percentage' => 'nullable|numeric|min:0|max:100'
        ]);

        $user = Auth::user();
        $course = $lesson->course;
        
        // Check if user is enrolled in the course
        if (!$user->enrollments()->where('course_id', $course->id)->exists()) {
            return response()->json(['error' => 'Not enrolled in this course'], 403);
        }

        // Update or create lesson progress
        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id
            ],
            [
                'completed' => $request->completed,
                'watched_percentage' => $request->watched_percentage ?? 100,
                'completed_at' => $request->completed ? now() : null
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully',
            'progress' => $progress
        ]);
    }
} 