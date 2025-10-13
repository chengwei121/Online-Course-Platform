<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the teacher's profile page
     */
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', 'Teacher profile not found.');
        }
        
        return view('teacher.profile.index', compact('user', 'teacher'));
    }
    
    /**
     * Update teacher profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->back()->with('error', 'Teacher profile not found.');
        }
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'teacher_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        DB::beginTransaction();
        try {
            // Update user information
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->save();
            
            // Update teacher information
            $teacher->name = $validated['teacher_name'];
            $teacher->phone = $validated['phone'];
            $teacher->bio = $validated['bio'];
            $teacher->qualification = $validated['qualification'];
            $teacher->specialization = $validated['specialization'];
            $teacher->experience_years = $validated['experience_years'];
            
            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($teacher->profile_picture && Storage::disk('public')->exists($teacher->profile_picture)) {
                    Storage::disk('public')->delete($teacher->profile_picture);
                }
                
                // Store new profile picture
                $path = $request->file('profile_picture')->store('teachers/profiles', 'public');
                $teacher->profile_picture = $path;
            }
            
            $teacher->save();
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }
    
    /**
     * Update teacher password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ]);
        
        $user = Auth::user();
        
        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }
        
        // Update password
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return redirect()->back()->with('success', 'Password updated successfully!');
    }
    
    /**
     * Remove profile picture
     */
    public function removeProfilePicture()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->back()->with('error', 'Teacher profile not found.');
        }
        
        // Delete profile picture if exists
        if ($teacher->profile_picture && Storage::disk('public')->exists($teacher->profile_picture)) {
            Storage::disk('public')->delete($teacher->profile_picture);
        }
        
        $teacher->profile_picture = null;
        $teacher->save();
        
        return redirect()->back()->with('success', 'Profile picture removed successfully!');
    }
}
