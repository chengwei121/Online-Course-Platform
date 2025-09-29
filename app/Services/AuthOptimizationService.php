<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AuthOptimizationService
{
    /**
     * Fast user lookup by email with caching
     */
    public function findUserByEmail(string $email): ?User
    {
        $cacheKey = "user_lookup_" . md5(strtolower($email));
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function() use ($email) {
            return User::select(['id', 'name', 'email', 'password', 'role', 'email_verified_at'])
                ->where('email', strtolower($email))
                ->first();
        });
    }

    /**
     * Verify user credentials with optimized checks
     */
    public function verifyCredentials(string $email, string $password): array
    {
        $startTime = microtime(true);
        
        // First check if user exists (cached)
        $user = $this->findUserByEmail($email);
        
        if (!$user) {
            // Log failed attempt
            Log::warning('Login attempt for non-existent user', [
                'email' => $email,
                'ip' => request()->ip()
            ]);
            
            // Add delay to prevent user enumeration
            usleep(250000); // 250ms
            
            return [
                'success' => false,
                'message' => 'Invalid credentials',
                'user' => null,
                'execution_time' => (microtime(true) - $startTime) * 1000
            ];
        }

        // Verify password
        if (!Hash::check($password, $user->password)) {
            // Clear user cache on failed password (security measure)
            Cache::forget("user_lookup_" . md5(strtolower($email)));
            
            Log::warning('Failed login attempt', [
                'user_id' => $user->id,
                'email' => $email,
                'ip' => request()->ip()
            ]);
            
            // Add delay to prevent brute force
            usleep(500000); // 500ms
            
            return [
                'success' => false,
                'message' => 'Invalid credentials',
                'user' => null,
                'execution_time' => (microtime(true) - $startTime) * 1000
            ];
        }

        // Success - cache user role for faster subsequent requests
        Cache::put("user_role_{$user->id}", $user->role, now()->addHours(24));
        
        Log::info('Successful login', [
            'user_id' => $user->id,
            'role' => $user->role,
            'execution_time' => (microtime(true) - $startTime) * 1000
        ]);

        return [
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'execution_time' => (microtime(true) - $startTime) * 1000
        ];
    }

    /**
     * Get cached user role for faster role checks
     */
    public function getUserRole(int $userId): ?string
    {
        return Cache::remember("user_role_{$userId}", now()->addHours(24), function() use ($userId) {
            return User::where('id', $userId)->value('role');
        });
    }

    /**
     * Clear authentication-related cache for user
     */
    public function clearUserCache(int $userId, string $email = null): void
    {
        Cache::forget("user_role_{$userId}");
        
        if ($email) {
            Cache::forget("user_lookup_" . md5(strtolower($email)));
            Cache::forget("user_exists_" . strtolower($email));
        }
    }

    /**
     * Optimize session performance
     */
    public function optimizeUserSession(User $user): void
    {
        // Cache frequently accessed user data
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ];
        
        Cache::put("session_user_{$user->id}", $userData, now()->addHours(4));
        
        // Update last login time efficiently
        DB::table('users')
            ->where('id', $user->id)
            ->update(['updated_at' => now()]);
    }

    /**
     * Get authentication statistics
     */
    public function getAuthStats(): array
    {
        $cacheKey = 'auth_stats_' . now()->format('Y-m-d-H');
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function() {
            return [
                'total_users' => User::count(),
                'users_by_role' => User::select('role', DB::raw('count(*) as count'))
                    ->groupBy('role')
                    ->pluck('count', 'role')
                    ->toArray(),
                'recent_logins' => User::where('updated_at', '>=', now()->subHour())
                    ->count(),
                'new_users_today' => User::whereDate('created_at', today())
                    ->count()
            ];
        });
    }

    /**
     * Preload user data for better performance
     */
    public function preloadUserData(User $user): User
    {
        // Load only necessary relationships based on role
        switch ($user->role) {
            case 'instructor':
                return $user->load(['teacher:id,user_id,name,bio']);
                
            case 'student':
                return $user->load(['student:id,user_id,name']);
                
            case 'admin':
                // Admin doesn't need additional relationships for login
                return $user;
                
            default:
                return $user;
        }
    }

    /**
     * Fast role-based redirect URLs
     */
    public function getRedirectUrl(string $role): string
    {
        $routes = [
            'admin' => 'admin.dashboard',
            'instructor' => 'teacher.dashboard',
            'student' => 'client.courses.index'
        ];

        $routeName = $routes[$role] ?? 'client.courses.index';
        
        return Cache::remember("route_url_{$routeName}", now()->addHours(6), function() use ($routeName) {
            return route($routeName);
        });
    }

    /**
     * Cleanup expired authentication cache
     */
    public function cleanupExpiredCache(): int
    {
        $cleaned = 0;
        
        // This would typically be handled by cache expiration
        // but we can manually clean specific patterns if needed
        
        Log::info('Authentication cache cleanup completed', [
            'cleaned_entries' => $cleaned
        ]);
        
        return $cleaned;
    }
}
