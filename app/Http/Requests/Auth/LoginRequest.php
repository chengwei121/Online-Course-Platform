<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');
        $remember = $this->boolean('remember');
        
        // Check if user exists in cache first (for faster failed attempts)
        $email = strtolower($this->input('email'));
        $userExistsKey = "user_exists_{$email}";
        
        $userExists = Cache::remember($userExistsKey, now()->addMinutes(5), function() use ($email) {
            return \App\Models\User::where('email', $email)->exists();
        });
        
        if (!$userExists) {
            // User doesn't exist - fail fast without hitting database again
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // Attempt authentication with optimized query
        if (!Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($this->throttleKey());
            
            // Clear user exists cache on failed password (might be deleted user)
            Cache::forget($userExistsKey);

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // Clear rate limiting on successful login
        RateLimiter::clear($this->throttleKey());
        
        // Update user exists cache on successful login
        Cache::put($userExistsKey, true, now()->addHours(1));
    }

    public function ensureIsNotRateLimited(): void
    {
        $key = $this->throttleKey();
        $maxAttempts = 5;
        $decayMinutes = 15;
        
        if (!RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($key);
        $minutes = ceil($seconds / 60);
        
        Log::warning('User account locked due to too many login attempts', [
            'ip' => $this->ip(),
            'email' => $this->input('email'),
            'attempts' => RateLimiter::attempts($key),
            'locked_for_seconds' => $seconds
        ]);

        throw ValidationException::withMessages([
            'email' => "Too many login attempts. Please try again in {$minutes} minute(s).",
        ]);
    }

    public function throttleKey(): string
    {
        $email = Str::lower($this->input('email', ''));
        $ip = $this->ip();
        
        // Use both IP and email for more granular rate limiting
        return "login_attempts_{$email}_{$ip}";
    }
} 