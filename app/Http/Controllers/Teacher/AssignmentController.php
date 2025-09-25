<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    /**
     * Show the form for creating a new assignment
     */
    public function create(Course $course, Lesson $lesson)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        return view('teacher.assignments.create', compact('course', 'lesson'));
    }

    /**
     * Store a newly created assignment
     */
    public function store(Request $request, Course $course, Lesson $lesson)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'required|string',
            'due_date' => 'nullable|date|after:today',
            'points' => 'nullable|integer|min:0|max:1000',
            'assignment_type' => 'required|in:quiz,project,essay,coding,presentation',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'estimated_time' => 'nullable|integer|min:1',
        ]);

        $assignment = Assignment::create([
            'lesson_id' => $lesson->id,
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'due_date' => $request->due_date,
            'points' => $request->points ?? 100,
            'assignment_type' => $request->assignment_type,
            'difficulty_level' => $request->difficulty_level,
            'estimated_time' => $request->estimated_time,
        ]);

        return redirect()
            ->route('teacher.courses.lessons.show', [$course, $lesson])
            ->with('success', 'Assignment created successfully!');
    }

    /**
     * Display the specified assignment
     */
    public function show(Course $course, Lesson $lesson, Assignment $assignment)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        // Ensure the assignment belongs to the lesson
        if ($assignment->lesson_id !== $lesson->id) {
            abort(404, 'Assignment not found in this lesson.');
        }

        return view('teacher.assignments.show', compact('course', 'lesson', 'assignment'));
    }

    /**
     * Show the form for editing the specified assignment
     */
    public function edit(Course $course, Lesson $lesson, Assignment $assignment)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        // Ensure the assignment belongs to the lesson
        if ($assignment->lesson_id !== $lesson->id) {
            abort(404, 'Assignment not found in this lesson.');
        }

        return view('teacher.assignments.edit', compact('course', 'lesson', 'assignment'));
    }

    /**
     * Update the specified assignment
     */
    public function update(Request $request, Course $course, Lesson $lesson, Assignment $assignment)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        // Ensure the assignment belongs to the lesson
        if ($assignment->lesson_id !== $lesson->id) {
            abort(404, 'Assignment not found in this lesson.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'required|string',
            'due_date' => 'nullable|date|after:today',
            'points' => 'nullable|integer|min:0|max:1000',
            'assignment_type' => 'required|in:quiz,project,essay,coding,presentation',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'estimated_time' => 'nullable|integer|min:1',
        ]);

        $assignment->update([
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'due_date' => $request->due_date,
            'points' => $request->points ?? 100,
            'assignment_type' => $request->assignment_type,
            'difficulty_level' => $request->difficulty_level,
            'estimated_time' => $request->estimated_time,
        ]);

        return redirect()
            ->route('teacher.assignments.show', [$course, $lesson, $assignment])
            ->with('success', 'Assignment updated successfully!');
    }

    /**
     * Remove the specified assignment
     */
    public function destroy(Course $course, Lesson $lesson, Assignment $assignment)
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        // Ensure the assignment belongs to the lesson
        if ($assignment->lesson_id !== $lesson->id) {
            abort(404, 'Assignment not found in this lesson.');
        }

        $assignment->delete();

        return redirect()
            ->route('teacher.courses.lessons.show', [$course, $lesson])
            ->with('success', 'Assignment deleted successfully!');
    }
}