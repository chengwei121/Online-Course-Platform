<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Cache Settings
    |--------------------------------------------------------------------------
    |
    | Configure caching options for better performance
    |
    */

    'cache_duration' => [
        'courses' => env('CACHE_COURSES_DURATION', 3600), // 1 hour
        'categories' => env('CACHE_CATEGORIES_DURATION', 7200), // 2 hours
        'trending' => env('CACHE_TRENDING_DURATION', 1800), // 30 minutes
        'instructors' => env('CACHE_INSTRUCTORS_DURATION', 3600), // 1 hour
    ],

    'database' => [
        'chunk_size' => env('DB_CHUNK_SIZE', 100),
        'pagination_limit' => env('PAGINATION_LIMIT', 9),
        'max_search_results' => env('MAX_SEARCH_RESULTS', 50),
    ],

    'images' => [
        'lazy_loading' => env('ENABLE_LAZY_LOADING', true),
        'placeholder_image' => env('PLACEHOLDER_IMAGE', 'images/course-placeholder.jpg'),
        'thumbnail_cache' => env('THUMBNAIL_CACHE_ENABLED', true),
    ],

    'frontend' => [
        'debounce_delay' => env('SEARCH_DEBOUNCE_DELAY', 1000), // milliseconds
        'animation_duration' => env('ANIMATION_DURATION', 300), // milliseconds
        'intersection_margin' => env('INTERSECTION_MARGIN', '100px'),
    ],
];