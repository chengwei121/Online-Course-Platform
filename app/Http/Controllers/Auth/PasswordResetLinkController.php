<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Queue the password reset email for instant response
        // We send success message immediately and process email in background
        Password::broker()->sendResetLink(
            $request->only('email')
        );

        // Always return success message immediately (instant response)
        // Email will be sent in background via queue
        return back()->with('status', 'We have emailed your password reset link! Please check your inbox (and spam folder).');
    }
}