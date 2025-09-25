<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CourseOptimizationService
{
    /**
     * Get optimized course list with caching
     */
    public function getOptimizedCourses($filters = [], $perPage = 9)
    {
        $cacheKey = 'courses_' . md5(serialize($filters) . $perPage);
        
        return Cache::remember($cacheKey, config('performance.cache_duration.courses'), function() use ($filters, $perPage) {
            $query = Course::query()
                ->select([
                    'id', 'title', 'slug', 'thumbnail', 'price', 'is_free',
                    'duration', 'average_rating', 'total_ratings', 'category_id',
                    'level', 'status', 'created_at', 'teacher_id'
                ])
                ->with([
                    'category:id,name',
                    'teacher:id,name'
                ])
                ->where('status', 'published');

            // Apply filters
            $this->applyFilters($query, $filters);

            return $query->orderByDesc('average_rating')
                        ->orderByDesc('total_ratings')
                        ->latest()
                        ->paginate($perPage);
        });
    }

    /**
     * Get cached categories with course counts
     */
    public function getCachedCategories()
    {
        return Cache::remember('categories_with_counts', config('performance.cache_duration.categories'), function() {
            return Category::select(['id', 'name', 'slug'])
                ->withCount(['courses' => function($query) {
                    $query->where('status', 'published');
                }])
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get trending courses with caching
     */
    public function getTrendingCourses($limit = 8)
    {
        return Cache::remember("trending_courses_{$limit}", config('performance.cache_duration.trending'), function() use ($limit) {
            return Course::select([
                    'id', 'title', 'slug', 'thumbnail', 'price', 'is_free',
                    'duration', 'average_rating', 'total_ratings', 'category_id', 'teacher_id'
                ])
                ->with([
                    'category:id,name',
                    'teacher:id,name'
                ])
                ->where('status', 'published')
                ->where('average_rating', '>=', 4.0)
                ->orderByDesc('total_ratings')
                ->orderByDesc('average_rating')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Clear course-related caches
     */
    public function clearCourseCaches()
    {
        $patterns = [
            'courses_*',
            'categories_with_counts',
            'trending_courses_*'
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
    }

    /**
     * Optimize course thumbnails with lazy loading
     */
    public function optimizeThumbnails($courses)
    {
        return $courses->map(function($course) {
            $course->thumbnail_url = $this->getThumbnailUrl($course->thumbnail);
            $course->placeholder_url = asset(config('performance.images.placeholder_image'));
            return $course;
        });
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, $filters)
    {
        if (!empty($filters['category'])) {
            $categories = is_array($filters['category']) ? $filters['category'] : [$filters['category']];
            $query->whereIn('category_id', $categories);
        }

        if (!empty($filters['level'])) {
            $levels = is_array($filters['level']) ? $filters['level'] : [$filters['level']];
            $query->whereIn('level', $levels);
        }

        if (!empty($filters['price_type'])) {
            $priceTypes = is_array($filters['price_type']) ? $filters['price_type'] : [$filters['price_type']];
            $query->where(function($q) use ($priceTypes) {
                foreach ($priceTypes as $priceType) {
                    if ($priceType === 'free') {
                        $q->orWhere('is_free', true);
                    } elseif ($priceType === 'premium') {
                        $q->orWhere('is_free', false);
                    }
                }
            });
        }

        if (!empty($filters['duration'])) {
            $durations = is_array($filters['duration']) ? $filters['duration'] : [$filters['duration']];
            $query->where(function($q) use ($durations) {
                foreach ($durations as $duration) {
                    switch ($duration) {
                        case 'short':
                            $q->orWhere('duration', '<=', 3);
                            break;
                        case 'medium':
                            $q->orWhereBetween('duration', [3, 6]);
                            break;
                        case 'long':
                            $q->orWhere('duration', '>', 6);
                            break;
                    }
                }
            });
        }

        if (!empty($filters['rating'])) {
            $ratings = is_array($filters['rating']) ? $filters['rating'] : [$filters['rating']];
            $minRating = min($ratings);
            $query->where('average_rating', '>=', $minRating);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
    }

    /**
     * Get optimized thumbnail URL
     */
    private function getThumbnailUrl($thumbnail)
    {
        if ($thumbnail && file_exists(storage_path('app/public/' . $thumbnail))) {
            return asset('storage/' . $thumbnail);
        }
        return asset(config('performance.images.placeholder_image'));
    }
}