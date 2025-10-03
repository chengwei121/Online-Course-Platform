<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        // If user is already authenticated, redirect immediately
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        
        // Use optimized login view if it exists
        $viewName = view()->exists('auth.login_optimized') ? 'auth.login_optimized' : 'auth.login';
        
        return view($viewName)->with([
            'canResetPassword' => true,
            'status' => session('status')
        ]);
    }

    public function store(LoginRequest $request)
    {
        try {
            // Start timing for performance monitoring
            $startTime = microtime(true);
            
            $request->authenticate();
            $request->session()->regenerate();
            
            // Get user with minimal data for faster queries
            $user = Auth::user(['id', 'name', 'email', 'role']);
            
            // Cache user role for faster subsequent requests
            Cache::put("user_role_{$user->id}", $user->role, now()->addHours(24));
            
            // Log successful login performance
            $executionTime = (microtime(true) - $startTime) * 1000;
            if ($executionTime > 1000) { // Log if login takes more than 1 second
                Log::info('Slow login detected', [
                    'user_id' => $user->id,
                    'execution_time_ms' => $executionTime
                ]);
            }
            
            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'success' => true,
                    'redirect' => $this->getRedirectUrl($user),
                    'user' => [
                        'name' => $user->name,
                        'role' => $user->role
                    ]
                ]);
            }
            
            return $this->redirectBasedOnRole($user);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return specific validation errors (like "credentials don't match")
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => $e->getMessage()
                ], 422);
            }
            
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('Login error', [
                'error' => $e->getMessage(),
                'email' => $request->input('email')
            ]);
            
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Authentication failed. Please try again.'
                ], 422);
            }
            
            throw $e;
        }
    }

    /**
     * Get redirect URL based on user role with caching
     */
    private function getRedirectUrl($user): string
    {
        $cacheKey = "redirect_url_{$user->role}";
        
        return Cache::remember($cacheKey, now()->addHours(1), function() use ($user) {
            switch ($user->role) {
                case 'admin':
                    return route('admin.dashboard');
                case 'instructor':
                    return route('teacher.dashboard');
                default:
                    return route('client.courses.index');
            }
        });
    }

    /**
     * Redirect user based on role
     */
    private function redirectBasedOnRole($user)
    {
        return redirect($this->getRedirectUrl($user));
    }

    public function destroy(Request $request)
    {
        $userId = Auth::id();
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear user-specific cache
        if ($userId) {
            Cache::forget("user_role_{$userId}");
        }
        
        if ($request->expectsJson()) {
            return new JsonResponse(['success' => true]);
        }
        
        return redirect('/');
    }
} 