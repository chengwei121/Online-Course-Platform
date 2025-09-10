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
    
    public function create()
    {
        return view('admin.clients.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'Client created successfully!');
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
            ->with('success', 'Client updated successfully!');
    }
    
    public function destroy(User $client)
    {
        if ($client->role !== 'student') {
            abort(404);
        }
        
        // Check if client has enrollments
        if ($client->enrollments()->count() > 0) {
            return redirect()->route('admin.clients.index')
                ->with('error', 'Cannot delete client with existing enrollments. Consider deactivating instead.');
        }
        
        $client->delete();
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'Client deleted successfully!');
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
    
    private function getCompletedCoursesCount(User $client)
    {
        $completedCount = 0;
        
        foreach ($client->enrollments as $enrollment) {
            $course = $enrollment->course;
            $totalLessons = $course->lessons->count();
            
            if ($totalLessons > 0) {
                $completedLessons = $client->lessonProgress()
                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                    ->where('is_completed', true)
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
                    ->where('is_completed', true)
                    ->count();
                
                if ($completedLessons > 0 && $completedLessons < $totalLessons) {
                    $inProgressCount++;
                }
            }
        }
        
        return $inProgressCount;
    }
}
