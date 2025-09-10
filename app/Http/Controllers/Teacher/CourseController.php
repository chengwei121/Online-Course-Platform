<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of teacher's courses
     */
    public function index()
    {
        $teacher = Auth::user()->teacher;
        
        $courses = Course::where('instructor_id', $teacher->id)
            ->withCount(['enrollments', 'lessons'])
            ->latest()
            ->paginate(10);

        return view('teacher.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course
     */
    public function create()
    {
        $categories = Category::all();
        return view('teacher.courses.create', compact('categories'));
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $teacher = Auth::user()->teacher;
        
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        $course = Course::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'instructor_id' => $teacher->id,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'difficulty_level' => $request->difficulty_level,
            'duration_hours' => $request->duration_hours,
            'thumbnail' => $thumbnailPath,
            'status' => 'draft',
        ]);

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Course created successfully!');
    }

    /**
     * Display the specified course
     */
    public function show(Course $course)
    {
        $this->authorize('view', $course);
        
        $lessons = $course->lessons()->orderBy('order')->get();
        $enrollments = $course->enrollments()->with('user')->latest()->take(10)->get();
        
        return view('teacher.courses.show', compact('course', 'lessons', 'enrollments'));
    }

    /**
     * Show the form for editing the specified course
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        
        $categories = Category::all();
        return view('teacher.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified course
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $thumbnailPath = $course->thumbnail;
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        $course->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'difficulty_level' => $request->difficulty_level,
            'duration_hours' => $request->duration_hours,
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        
        // Delete thumbnail if exists
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        
        $course->delete();

        return redirect()->route('teacher.courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    /**
     * Publish/unpublish course
     */
    public function toggleStatus(Course $course)
    {
        $this->authorize('update', $course);
        
        $course->update([
            'status' => $course->status === 'published' ? 'draft' : 'published'
        ]);

        $status = $course->status === 'published' ? 'published' : 'unpublished';
        
        return back()->with('success', "Course {$status} successfully!");
    }
}
