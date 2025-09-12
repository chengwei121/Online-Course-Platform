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

        if (Auth::attempt($credentials) && Auth::user()->isTeacher()) {
            $request->session()->regenerate();
            return redirect()->intended(route('teacher.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or you are not a teacher.',
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
