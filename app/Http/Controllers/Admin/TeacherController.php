<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index(Request $request)
    {
        $query = Teacher::with('user')->withCount('courses');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('department', 'like', "%{$search}%")
              ->orWhere('qualification', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Department filter
        if ($request->has('department') && !empty($request->department)) {
            $query->where('department', $request->department);
        }

        $teachers = $query->latest()
                         ->paginate(15)
                         ->withQueryString();

        // Statistics
        $stats = [
            'total' => Teacher::count(),
            'active' => Teacher::where('status', 'active')->count(),
            'inactive' => Teacher::where('status', 'inactive')->count(),
            'with_courses' => Teacher::has('courses')->count(),
        ];

        return view('admin.teachers.index', compact('teachers', 'stats'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('admin.teachers.create');
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => [
                'required',
                'string',
                'regex:/^(0[1-9][0-9]?|1[0-9])-[0-9]{3,4}-[0-9]{4}$/'
            ],
            'qualification' => 'required|in:Certificate,Diploma,Bachelor\'s Degree,Master\'s Degree,PhD/Doctorate,Professional Certification',
            'department' => 'required|in:Computer Science & IT,Engineering,Business & Management,Mathematics & Statistics,Science & Technology,Arts & Design,Languages & Literature,Health & Medicine,Education & Training,Finance & Accounting,Marketing & Sales,Other',
            'bio' => 'nullable|string|max:1000',
            'hourly_rate' => 'nullable|numeric|min:0|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive'
        ], [
            'name.regex' => 'Name may only contain letters and spaces.',
            'phone.required' => 'Malaysian phone number is required.',
            'phone.regex' => 'Please enter a valid Malaysian phone number format (XX-XXX-XXXX).',
            'qualification.in' => 'Please select a valid qualification.',
            'department.in' => 'Please select a valid department.',
            'hourly_rate.max' => 'Hourly rate cannot exceed RM 1000.',
            'bio.max' => 'Biography cannot exceed 1000 characters.'
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'instructor'
        ]);

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('teachers/profiles', 'public');
        }

        // Create teacher profile
        Teacher::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'qualification' => $validated['qualification'],
            'department' => $validated['department'],
            'bio' => $validated['bio'],
            'hourly_rate' => $validated['hourly_rate'],
            'profile_picture' => $profilePicturePath,
            'status' => $validated['status']
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher account created successfully!');
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'courses' => function($query) {
            $query->withCount('enrollments');
        }]);

        return view('admin.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone' => [
                'required',
                'string',
                'regex:/^(0[1-9][0-9]?|1[0-9])-[0-9]{3,4}-[0-9]{4}$/'
            ],
            'qualification' => 'required|in:Certificate,Diploma,Bachelor\'s Degree,Master\'s Degree,PhD/Doctorate,Professional Certification',
            'department' => 'required|in:Computer Science & IT,Engineering,Business & Management,Mathematics & Statistics,Science & Technology,Arts & Design,Languages & Literature,Health & Medicine,Education & Training,Finance & Accounting,Marketing & Sales,Other',
            'bio' => 'nullable|string|max:1000',
            'hourly_rate' => 'nullable|numeric|min:0|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive'
        ], [
            'name.regex' => 'Name may only contain letters and spaces.',
            'phone.required' => 'Malaysian phone number is required.',
            'phone.regex' => 'Please enter a valid Malaysian phone number format (XX-XXX-XXXX).',
            'qualification.in' => 'Please select a valid qualification.',
            'department.in' => 'Please select a valid department.',
            'hourly_rate.max' => 'Hourly rate cannot exceed RM 1000.',
            'bio.max' => 'Biography cannot exceed 1000 characters.'
        ]);

        // Update user account
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $teacher->user->update($userData);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture
            if ($teacher->profile_picture) {
                Storage::disk('public')->delete($teacher->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('teachers/profiles', 'public');
        }

        // Update teacher profile
        $teacher->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'qualification' => $validated['qualification'],
            'department' => $validated['department'],
            'bio' => $validated['bio'],
            'hourly_rate' => $validated['hourly_rate'],
            'profile_picture' => $validated['profile_picture'] ?? $teacher->profile_picture,
            'status' => $validated['status']
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully!');
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy(Teacher $teacher)
    {
        // Check if teacher has courses
        if ($teacher->courses()->count() > 0) {
            return redirect()->route('admin.teachers.index')
                ->with('error', 'Cannot delete teacher with existing courses. Please reassign or delete courses first.');
        }

        // Delete profile picture
        if ($teacher->profile_picture) {
            Storage::disk('public')->delete($teacher->profile_picture);
        }

        // Delete teacher and user
        $teacher->user->delete();
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully!');
    }

    /**
     * Toggle teacher status.
     */
    public function toggleStatus(Teacher $teacher)
    {
        $teacher->update([
            'status' => $teacher->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->back()
            ->with('success', 'Teacher status updated successfully!');
    }
}
