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
    public function index(Request $request)
    {
        $teacher = Auth::user()->teacher;
        
        $perPage = $request->get('per_page', 12);
        // Validate per_page value
        if (!in_array($perPage, [12, 24, 48])) {
            $perPage = 12;
        }
        
        $query = Course::where('teacher_id', $teacher->id)
            ->withCount(['enrollments', 'lessons']);
            
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        // Status filter
        if ($request->filled('status') && $request->get('status') !== 'all') {
            $query->where('status', $request->get('status'));
        }
        
        $courses = $query->latest()->paginate($perPage);
        
        // Preserve query parameters in pagination links
        $courses->appends($request->query());

        return view('teacher.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course
     */
    public function create(Request $request)
    {
        $categories = Category::all();
        $selectedCategoryId = $request->get('category_id');
        $coursesByCategory = [];
        
        if ($selectedCategoryId) {
            $coursesByCategory = Course::where('category_id', $selectedCategoryId)
                ->with(['instructor', 'category'])
                ->withCount(['enrollments', 'lessons'])
                ->latest()
                ->get()
                ->groupBy(function($course) {
                    return $course->teacher_id == Auth::user()->teacher->id ? 'my_courses' : 'other_courses';
                });
        }
        
        return view('teacher.courses.create', compact('categories', 'selectedCategoryId', 'coursesByCategory'));
    }

    /**
     * Get courses by category (AJAX endpoint)
     */
    public function getCoursesByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');
        $teacher = Auth::user()->teacher;
        
        if (!$categoryId) {
            return response()->json(['courses' => []]);
        }
        
        $courses = Course::where('category_id', $categoryId)
            ->with(['instructor', 'category'])
            ->withCount(['enrollments', 'lessons'])
            ->latest()
            ->get();
            
        $courseData = $courses->map(function($course) use ($teacher) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => Str::limit($course->description, 100),
                'price' => $course->price,
                'level' => $course->level,
                'status' => $course->status,
                'thumbnail_url' => $course->thumbnail_url,
                'instructor_name' => $course->instructor->name,
                'enrollments_count' => $course->enrollments_count,
                'lessons_count' => $course->lessons_count,
                'is_my_course' => $course->teacher_id == ($teacher ? $teacher->id : null),
                'created_at' => $course->created_at->diffForHumans(),
                'edit_url' => route('teacher.courses.edit', $course),
                'view_url' => route('teacher.courses.show', $course)
            ];
        });
        
        return response()->json(['courses' => $courseData]);
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
            'price' => 'nullable|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'learning_hours' => 'required|integer|min:1',
            'skills_to_learn' => 'nullable|string',
            'is_free' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $teacher = Auth::user()->teacher;
        
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('images/courses', 'public');
        }

        // Process skills to learn
        $skillsArray = null;
        if ($request->filled('skills_to_learn')) {
            $skillsArray = array_map('trim', explode(',', $request->skills_to_learn));
            $skillsArray = array_filter($skillsArray); // Remove empty values
        }

        $course = Course::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'teacher_id' => $teacher->id,
            'category_id' => $request->category_id,
            'price' => $request->is_free ? 0 : ($request->price ?? 0),
            'level' => $request->level,
            'learning_hours' => $request->learning_hours,
            'skills_to_learn' => $skillsArray,
            'is_free' => (bool) $request->is_free,
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
        
        // Load course with counts and related data
        $course->loadCount(['enrollments', 'lessons']);
        $course->load(['category', 'instructor']);
        
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
        
        $categories = Category::select('id', 'name')->get();
        $course->loadCount(['enrollments', 'lessons']);
        
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
            'price' => 'nullable|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'learning_hours' => 'required|integer|min:1',
            'skills_to_learn' => 'nullable|string',
            'is_free' => 'nullable|boolean',
            'status' => 'nullable|in:draft,published,archived',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $thumbnailPath = $course->thumbnail;
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('images/courses', 'public');
        }

        // Process skills to learn
        $skillsArray = null;
        if ($request->filled('skills_to_learn')) {
            $skillsArray = array_map('trim', explode(',', $request->skills_to_learn));
            $skillsArray = array_filter($skillsArray); // Remove empty values
        }

        $course->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->is_free ? 0 : ($request->price ?? 0),
            'level' => $request->level,
            'learning_hours' => $request->learning_hours,
            'skills_to_learn' => $skillsArray,
            'is_free' => (bool) $request->is_free,
            'status' => $request->status ?? $course->status,
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
