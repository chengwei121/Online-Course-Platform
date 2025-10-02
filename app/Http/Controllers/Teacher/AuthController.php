<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Show the teacher registration form
     */
    public function showRegister()
    {
        return view('teacher.auth.register');
    }

    /**
     * Handle teacher registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:15'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'department' => ['nullable', 'string', 'max:255'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'instructor',
        ]);

        // Create teacher profile
        Teacher::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'qualification' => $request->qualification,
            'bio' => $request->bio,
            'department' => $request->department,
            'hourly_rate' => $request->hourly_rate,
        ]);

        Auth::login($user);

        return redirect()->route('teacher.dashboard');
    }

    /**
     * Show the teacher login form
     */
    public function showLogin()
    {
        return view('teacher.auth.login');
    }

    /**
     * Handle teacher login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            if ($user->isTeacher()) {
                $request->session()->regenerate();
                
                // Return JSON response for AJAX requests
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'redirect' => route('teacher.dashboard'),
                        'message' => 'Login successful'
                    ]);
                }
                
                return redirect()->intended(route('teacher.dashboard'));
            } else {
                Auth::logout();
                $error = 'Access denied. Teachers only.';
            }
        } else {
            $error = 'The provided credentials do not match our records.';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $error,
                'errors' => ['email' => [$error]]
            ], 422);
        }

        return back()->withErrors([
            'email' => $error,
        ])->onlyInput('email');
    }

    /**
     * Handle teacher logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('welcome');
    }
}
