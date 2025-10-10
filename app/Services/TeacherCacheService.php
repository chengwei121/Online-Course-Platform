<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class TeacherCacheService
{
    /**
     * Clear all caches for a specific teacher
     *
     * @param int $teacherId
     * @return void
     */
    public static function clearTeacherCache(int $teacherId): void
    {
        // Clear dashboard cache
        Cache::forget("teacher_dashboard_{$teacherId}");
        
        // Clear courses cache
        Cache::forget("teacher_courses_{$teacherId}");
        Cache::forget("teacher_courses_dropdown_{$teacherId}");
        
        // Clear students cache
        Cache::forget("teacher_students_stats_{$teacherId}");
        
        // Clear any other teacher-specific caches
        Cache::tags(["teacher_{$teacherId}"])->flush();
    }
    
    /**
     * Clear dashboard cache for a teacher
     *
     * @param int $teacherId
     * @return void
     */
    public static function clearDashboardCache(int $teacherId): void
    {
        Cache::forget("teacher_dashboard_{$teacherId}");
    }
    
    /**
     * Clear students cache for a teacher
     *
     * @param int $teacherId
     * @return void
     */
    public static function clearStudentsCache(int $teacherId): void
    {
        Cache::forget("teacher_students_stats_{$teacherId}");
        Cache::forget("teacher_courses_{$teacherId}");
    }
    
    /**
     * Clear courses cache for a teacher
     *
     * @param int $teacherId
     * @return void
     */
    public static function clearCoursesCache(int $teacherId): void
    {
        Cache::forget("teacher_courses_{$teacherId}");
        Cache::forget("teacher_courses_dropdown_{$teacherId}");
    }
    
    /**
     * Warm up cache for a teacher (pre-load frequently accessed data)
     *
     * @param int $teacherId
     * @return void
     */
    public static function warmUpCache(int $teacherId): void
    {
        // This method can be called after data updates to pre-populate cache
        // Implementation depends on your specific caching strategy
    }
}
