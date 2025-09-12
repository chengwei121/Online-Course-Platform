<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['instructor', 'category', 'enrollments']);
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by instructor
        if ($request->has('instructor') && $request->instructor) {
            $query->where('instructor_id', $request->instructor);
        }
        
        $courses = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get filter options
        $categories = Category::orderBy('name')->get();
        $instructors = Instructor::orderBy('name')->get();
        
        return view('admin.courses.index', compact('courses', 'categories', 'instructors'));
    }
    
    public function show(Course $course)
    {
        $course->load(['instructor', 'category', 'lessons', 'enrollments.user', 'reviews.user']);
        
        // Calculate statistics
        $stats = [
            'total_enrollments' => $course->enrollments->count(),
            'total_lessons' => $course->lessons->count(),
            'total_reviews' => $course->reviews->count(),
            'average_rating' => $course->average_rating ?? 0,
            'completion_rate' => $this->calculateCompletionRate($course),
            'revenue' => $course->enrollments->sum(function($enrollment) use ($course) {
                return $course->is_free ? 0 : $course->price;
            })
        ];
        
        return view('admin.courses.show', compact('course', 'stats'));
    }
    

    
    public function edit(Course $course)
    {
        $categories = Category::orderBy('name')->get();
        $instructors = Instructor::orderBy('name')->get();
        
        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }
    
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'instructor_id' => 'required|exists:instructors,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|string',
            'learning_hours' => 'nullable|integer|min:1',
            'skills_to_learn' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived'
        ]);
        
        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_free'] = $request->price == 0;
        
        // Handle skills as array
        if ($request->skills_to_learn) {
            $data['skills_to_learn'] = array_map('trim', explode(',', $request->skills_to_learn));
        }
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/courses', $filename, 'public');
            $data['thumbnail'] = $path;
        }
        
        $course->update($data);
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully!');
    }
    
    public function destroy(Course $course)
    {
        // Check if course has enrollments
        if ($course->enrollments()->count() > 0) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'Cannot delete course with existing enrollments. Archive it instead.');
        }
        
        // Delete thumbnail
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        
        $course->delete();
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully!');
    }
    
    public function toggleStatus(Course $course)
    {
        $newStatus = $course->status === 'published' ? 'draft' : 'published';
        $course->update(['status' => $newStatus]);
        
        $message = $newStatus === 'published' ? 'Course published successfully!' : 'Course moved to draft!';
        
        return redirect()->back()->with('success', $message);
    }
    
    private function calculateCompletionRate(Course $course)
    {
        $totalEnrollments = $course->enrollments->count();
        if ($totalEnrollments === 0) return 0;
        
        $completedEnrollments = $course->enrollments->filter(function($enrollment) use ($course) {
            $totalLessons = $course->lessons->count();
            if ($totalLessons === 0) return false;
            
            $completedLessons = $enrollment->user->lessonProgress()
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->where('completed', true)
                ->count();
                
            return $completedLessons >= $totalLessons;
        })->count();
        
        return round(($completedEnrollments / $totalEnrollments) * 100, 1);
    }
}
