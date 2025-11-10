<?php

// Temporary Debug Route - Add this to routes/web.php temporarily

Route::get('/debug-auth', function() {
    if (!auth()->check()) {
        return response()->json([
            'authenticated' => false,
            'message' => 'User is not logged in',
            'session_id' => session()->getId(),
            'session_driver' => config('session.driver'),
        ]);
    }
    
    $user = auth()->user();
    
    return response()->json([
        'authenticated' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
        'checks' => [
            'isAdmin' => $user->isAdmin(),
            'isTeacher' => $user->isTeacher(),
            'isStudent' => $user->isStudent(),
            'has_teacher_record' => $user->teacher ? true : false,
            'teacher_id' => $user->teacher?->id,
        ],
        'session' => [
            'session_id' => session()->getId(),
            'session_driver' => config('session.driver'),
            'auth_guard' => config('auth.defaults.guard'),
        ],
        'env' => [
            'app_env' => app()->environment(),
            'app_debug' => config('app.debug'),
        ]
    ]);
})->name('debug.auth');
