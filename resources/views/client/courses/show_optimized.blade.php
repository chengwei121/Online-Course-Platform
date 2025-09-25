@extends('layouts.client')

@section('title', $course->title)

@push('styles')
<style>
    /* Optimized course show page styles */
    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .instructor-card { transition: all 0.3s ease; }
    .instructor-card:hover { transform: translateY(-2px); }
    
    .review-item { transition: opacity 0.3s ease; }
    
    .course-content {
        min-height: 60vh;
    }
    
    /* Lazy loading improvements */
    img[data-src] { 
        opacity: 0; 
        transition: opacity 0.3s;
        background: #f3f4f6;
    }
    img[data-loaded] { opacity: 1; }
    
    /* Performance optimizations */
    .gpu-accelerated { transform: translateZ(0); }
    .will-change-transform { will-change: transform; }
    
    /* Optimized text rendering */
    .text-content { 
        text-rendering: optimizeSpeed;
        font-display: swap;
    }
    
    /* Skeleton loading states */
    .skeleton-box {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 8px;
    }
    
    .skeleton-text {
        height: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .skeleton-title {
        height: 1.5rem;
        width: 70%;
        margin-bottom: 1rem;
    }
    
    /* Optimize button interactions */
    .btn-optimized {
        transform: translateZ(0);
        backface-visibility: hidden;
        perspective: 1000px;
        transition: all 0.2s ease;
    }
    
    .btn-optimized:hover {
        transform: translateY(-1px) translateZ(0);
    }
    
    /* Reduce layout shifts */
    .fixed-aspect-ratio {
        aspect-ratio: 16/9;
        width: 100%;
        height: auto;
    }
    
    /* Optimize reviews section */
    .review-grid {
        contain: layout style paint;
    }
    
    /* Optimize lesson list */
    .lesson-item {
        contain: layout style;
        transition: transform 0.2s ease;
    }
    
    .lesson-item:hover {
        transform: translateX(4px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 mt-16">
        
        <!-- Course Hero Section - Optimized -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 gpu-accelerated">
            <div class="lg:flex">
                <!-- Course Image -->
                <div class="lg:w-1/2">
                    <div class="relative fixed-aspect-ratio bg-gray-200">
                        <!-- Loading skeleton -->
                        <div id="imageSkeleton" class="absolute inset-0 skeleton-box"></div>
                        
                        @if($course->thumbnail)
                            <img data-src="{{ asset('storage/' . $course->thumbnail) }}" 
                                 alt="{{ $course->title }}"
                                 class="absolute inset-0 w-full h-full object-cover"
                                 onload="this.setAttribute('data-loaded', ''); document.getElementById('imageSkeleton').style.display='none';"
                                 onerror="this.src='{{ asset('images/default-course.jpg') }}'; this.setAttribute('data-loaded', ''); document.getElementById('imageSkeleton').style.display='none';">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-indigo-400 to-purple-600">
                                <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Course Info -->
                <div class="lg:w-1/2 p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4 text-content">{{ $course->title }}</h1>
                    <p class="text-gray-600 text-lg mb-6 text-content">{{ $course->description }}</p>
                    
                    <!-- Course Stats -->
                    <div class="flex flex-wrap gap-6 mb-6 text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $course->lessons->sum('duration') }} minutes
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            {{ $course->lessons->count() }} lessons
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ $course->enrollments->count() }} students
                        </div>
                    </div>
                    
                    <!-- Price and Enrollment -->
                    <div class="flex items-center justify-between">
                        <div>
                            @if($course->is_free)
                                <span class="text-3xl font-bold text-green-600">Free</span>
                            @else
                                <span class="text-3xl font-bold text-indigo-600">${{ number_format($course->price, 2) }}</span>
                            @endif
                        </div>
                        
                        @if($enrolled)
                            <a href="{{ route('client.courses.learn', ['course' => $course->slug, 'lesson' => $course->lessons->first()->id]) }}" 
                               class="btn-optimized inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10v4a2 2 0 002 2h2a2 2 0 002-2v-4M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M13 7a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Continue Learning
                            </a>
                        @else
                            @if($course->is_free)
                                <form action="{{ route('client.courses.enroll', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-optimized inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Enroll for Free
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('client.courses.purchase', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-optimized inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 21"></path>
                                        </svg>
                                        Purchase Course
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 course-content">
                
                <!-- Course Content Tabs -->
                <div class="bg-white rounded-xl shadow-sm mb-8 gpu-accelerated">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                            <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active" 
                                    data-target="lessons">
                                Lessons
                            </button>
                            <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" 
                                    data-target="about">
                                About
                            </button>
                            <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" 
                                    data-target="reviews">
                                Reviews ({{ $course->reviews->count() }})
                            </button>
                        </nav>
                    </div>
                    
                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Lessons Tab -->
                        <div id="lessons" class="tab-content">
                            <div class="space-y-4">
                                @foreach($course->lessons as $index => $lesson)
                                    <div class="lesson-item flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all duration-200" 
                                         @if($index > 7) data-lazy-load @endif>
                                        <div class="flex-shrink-0 mr-4">
                                            @if($lesson->video_url)
                                                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10v4a2 2 0 002 2h2a2 2 0 002-2v-4M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M13 7a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-medium text-gray-900 truncate">{{ $lesson->title }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ $lesson->duration }} minutes</p>
                                        </div>
                                        @if($enrolled)
                                            @php
                                                $progress = auth()->user()->lessonProgress()
                                                    ->where('lesson_id', $lesson->id)
                                                    ->first();
                                            @endphp
                                            @if($progress && $progress->completed)
                                                <div class="flex-shrink-0 ml-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Completed
                                                    </span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- About Tab -->
                        <div id="about" class="tab-content hidden">
                            <div class="prose prose-lg max-w-none text-content">
                                {!! $course->long_description ?? $course->description !!}
                            </div>
                        </div>
                        
                        <!-- Reviews Tab - Lazy loaded -->
                        <div id="reviews" class="tab-content hidden">
                            <div id="reviewsContainer">
                                <!-- Reviews will be loaded here -->
                                <div class="text-center py-8">
                                    <div class="skeleton-box w-full h-32"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Instructor Card - Optimized -->
                <div class="instructor-card bg-white rounded-xl shadow-sm p-6 mb-8 gpu-accelerated">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Instructor</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($course->instructor && $course->instructor->profile_picture)
                                <div class="relative">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full skeleton-box"></div>
                                    <img data-src="{{ asset('storage/' . $course->instructor->profile_picture) }}" 
                                         alt="{{ $course->instructor->name }}"
                                         class="absolute inset-0 w-16 h-16 rounded-full object-cover"
                                         onload="this.setAttribute('data-loaded', ''); this.previousElementSibling.style.display='none';"
                                         onerror="this.style.display='none';">
                                </div>
                            @else
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xl font-semibold">
                                        {{ substr($course->instructor ? $course->instructor->name : 'Unknown', 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-lg font-medium text-gray-900 truncate">
                                {{ $course->instructor ? $course->instructor->name : 'Unknown Instructor' }}
                            </p>
                            <p class="text-sm text-gray-500">Course Instructor</p>
                        </div>
                    </div>
                    
                    @if($course->instructor)
                        <div class="mt-4 space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ $course->instructor->courses->count() }} courses
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $course->instructor->courses->sum(function($c) { return $c->enrollments->count(); }) }} students
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Course Stats -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Stats</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Category</span>
                            <span class="font-medium text-gray-900">{{ $course->category->name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Level</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($course->level ?? 'Beginner') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-medium text-gray-900">{{ $course->lessons->sum('duration') }} min</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Students</span>
                            <span class="font-medium text-gray-900">{{ $course->enrollments->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Rating</span>
                            <div class="flex items-center">
                                @php
                                    $averageRating = $course->reviews->avg('rating') ?? 0;
                                    $fullStars = floor($averageRating);
                                    $hasHalfStar = $averageRating - $fullStars >= 0.5;
                                @endphp
                                
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $fullStars)
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    @elseif($i == $fullStars + 1 && $hasHalfStar)
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="half-star">
                                                    <stop offset="50%" stop-color="currentColor"/>
                                                    <stop offset="50%" stop-color="#e5e7eb"/>
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#half-star)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="ml-2 text-sm font-medium text-gray-900">{{ number_format($averageRating, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Optimized course show page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
    initializeLazyLoading();
    initializeImageLazyLoading();
});

// Tab functionality
function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            
            // Update button states
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            this.classList.add('active', 'border-indigo-500', 'text-indigo-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Update content visibility
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.remove('hidden');
                
                // Load reviews if reviews tab is clicked
                if (targetId === 'reviews') {
                    loadReviews();
                }
            }
        });
    });
}

// Lazy loading for lessons
function initializeLazyLoading() {
    const lazyElements = document.querySelectorAll('[data-lazy-load]');
    if ('IntersectionObserver' in window) {
        const lazyObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.removeAttribute('data-lazy-load');
                    lazyObserver.unobserve(entry.target);
                }
            });
        }, { rootMargin: '50px' });
        
        lazyElements.forEach(el => lazyObserver.observe(el));
    }
}

// Lazy loading for images
function initializeImageLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.getAttribute('data-src');
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        }, { rootMargin: '50px' });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for browsers without IntersectionObserver
        lazyImages.forEach(img => {
            img.src = img.getAttribute('data-src');
            img.removeAttribute('data-src');
        });
    }
}

// Load reviews dynamically
let reviewsLoaded = false;
function loadReviews() {
    if (reviewsLoaded) return;
    
    const reviewsContainer = document.getElementById('reviewsContainer');
    const courseId = {{ $course->id }};
    
    // Show loading state
    reviewsContainer.innerHTML = `
        <div class="text-center py-8">
            <div class="skeleton-box w-full h-32 mb-4"></div>
            <div class="skeleton-box w-3/4 h-4 mx-auto mb-2"></div>
            <div class="skeleton-box w-1/2 h-4 mx-auto"></div>
        </div>
    `;
    
    // Fetch reviews
    fetch(`/api/courses/${courseId}/reviews`)
        .then(response => response.json())
        .then(data => {
            reviewsLoaded = true;
            if (data.reviews && data.reviews.length > 0) {
                renderReviews(data.reviews);
            } else {
                renderNoReviews();
            }
        })
        .catch(error => {
            console.error('Error loading reviews:', error);
            renderNoReviews();
        });
}

function renderReviews(reviews) {
    const reviewsContainer = document.getElementById('reviewsContainer');
    let reviewsHTML = '<div class="space-y-6 review-grid">';
    
    reviews.forEach(review => {
        reviewsHTML += `
            <div class="review-item border border-gray-200 rounded-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-lg font-semibold">
                                ${review.user.name.charAt(0).toUpperCase()}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">${review.user.name}</p>
                            <p class="text-sm text-gray-500">${formatDate(review.created_at)}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        ${renderStars(review.rating)}
                    </div>
                </div>
                <p class="text-gray-700">${review.comment}</p>
            </div>
        `;
    });
    
    reviewsHTML += '</div>';
    
    // Add review form if user is enrolled
    @if($enrolled && !auth()->user()->courseReviews()->where('course_id', $course->id)->exists())
        reviewsHTML += `
            <div class="mt-8 border-t border-gray-200 pt-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Write a Review</h4>
                <form action="{{ route('client.courses.review', $course) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <div class="flex space-x-1">
                            ${[1,2,3,4,5].map(star => `
                                <button type="button" class="star-rating text-gray-300 hover:text-yellow-400 text-2xl transition-colors duration-200" data-rating="${star}">★</button>
                            `).join('')}
                        </div>
                        <input type="hidden" name="rating" id="rating" required>
                    </div>
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                        <textarea name="comment" id="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Share your thoughts about this course..." required></textarea>
                    </div>
                    <button type="submit" class="btn-optimized inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        Submit Review
                    </button>
                </form>
            </div>
        `;
    @endif
    
    reviewsContainer.innerHTML = reviewsHTML;
    
    // Initialize star rating functionality
    initializeStarRating();
}

function renderNoReviews() {
    const reviewsContainer = document.getElementById('reviewsContainer');
    let noReviewsHTML = `
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews yet</h3>
            <p class="mt-1 text-sm text-gray-500">Be the first to review this course!</p>
        </div>
    `;
    
    // Add review form if user is enrolled
    @if($enrolled && !auth()->user()->courseReviews()->where('course_id', $course->id)->exists())
        noReviewsHTML += `
            <div class="border-t border-gray-200 pt-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Write the First Review</h4>
                <form action="{{ route('client.courses.review', $course) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <div class="flex space-x-1">
                            ${[1,2,3,4,5].map(star => `
                                <button type="button" class="star-rating text-gray-300 hover:text-yellow-400 text-2xl transition-colors duration-200" data-rating="${star}">★</button>
                            `).join('')}
                        </div>
                        <input type="hidden" name="rating" id="rating" required>
                    </div>
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                        <textarea name="comment" id="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Share your thoughts about this course..." required></textarea>
                    </div>
                    <button type="submit" class="btn-optimized inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        Submit Review
                    </button>
                </form>
            </div>
        `;
    @endif
    
    reviewsContainer.innerHTML = noReviewsHTML;
    initializeStarRating();
}

function renderStars(rating) {
    let starsHTML = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            starsHTML += '<svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>';
        } else {
            starsHTML += '<svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>';
        }
    }
    return starsHTML;
}

function initializeStarRating() {
    const starButtons = document.querySelectorAll('.star-rating');
    const ratingInput = document.getElementById('rating');
    
    starButtons.forEach(button => {
        button.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            
            // Update visual state
            starButtons.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
            
            // Update hidden input
            if (ratingInput) {
                ratingInput.value = rating;
            }
        });
    });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}
</script>
@endpush

@endsection