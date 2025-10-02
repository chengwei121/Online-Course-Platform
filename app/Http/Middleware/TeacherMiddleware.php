<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if (!$user->isTeacher()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Access denied. Teachers only.');
        }

        // Preload teacher relationship to avoid N+1 queries
        if (!$user->relationLoaded('teacher')) {
            $user->load('teacher:id,user_id,name,email,status');
        }

        return $next($request);
    }
}
