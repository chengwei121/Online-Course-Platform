<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Teacher Panel Performance Settings
    |--------------------------------------------------------------------------
    |
    | Configuration options to optimize teacher panel performance
    |
    */

    // Cache TTL (Time To Live) in seconds
    'cache' => [
        'dashboard' => env('TEACHER_DASHBOARD_CACHE_TTL', 300), // 5 minutes
        'students_stats' => env('TEACHER_STUDENTS_STATS_CACHE_TTL', 120), // 2 minutes
        'courses' => env('TEACHER_COURSES_CACHE_TTL', 300), // 5 minutes
        'courses_dropdown' => env('TEACHER_COURSES_DROPDOWN_CACHE_TTL', 600), // 10 minutes
    ],

    // Pagination settings
    'pagination' => [
        'students_per_page' => env('TEACHER_STUDENTS_PER_PAGE', 15),
        'courses_per_page' => env('TEACHER_COURSES_PER_PAGE', 10),
        'enrollments_per_page' => env('TEACHER_ENROLLMENTS_PER_PAGE', 20),
    ],

    // Query optimization
    'query' => [
        // Limit for dashboard recent items
        'recent_items_limit' => env('TEACHER_RECENT_ITEMS_LIMIT', 5),
        
        // Enable query result caching
        'enable_query_cache' => env('TEACHER_ENABLE_QUERY_CACHE', true),
        
        // Use chunking for large datasets
        'chunk_size' => env('TEACHER_CHUNK_SIZE', 100),
    ],

    // Asset optimization
    'assets' => [
        // Enable CDN for static assets
        'use_cdn' => env('TEACHER_USE_CDN', true),
        
        // Lazy load images
        'lazy_load_images' => env('TEACHER_LAZY_LOAD_IMAGES', true),
        
        // Defer non-critical JavaScript
        'defer_javascript' => env('TEACHER_DEFER_JAVASCRIPT', true),
    ],

    // Database optimization
    'database' => [
        // Use database indexes
        'use_indexes' => true,
        
        // Enable eager loading to prevent N+1 queries
        'eager_load_relations' => true,
        
        // Select only required columns
        'select_specific_columns' => true,
    ],
];
