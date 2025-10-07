@extends('layouts.client')

@section('title', $course->title . ' - Learning')

@push('styles')
<style>
    /* Optimized learning page styles */
    .lesson-item { transition: all 0.2s ease; }
    .lesson-item:hover { transform: translateX(2px); }
    
    .video-container { 
        position: relative; 
        background: #000;
        min-height: 300px;
    }
    
    .progress-bar-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
    }
    
    .prose h1, .prose h2, .prose h3 { margin-top: 1.5rem; margin-bottom: 1rem; }
    .prose p { margin-bottom: 1rem; }
    .prose ul, .prose ol { margin-bottom: 1rem; padding-left: 1.5rem; }
    
    /* Optimize font loading */
    .lesson-title { font-display: swap; }
    
    /* Performance optimizations */
    .gpu-accelerated { transform: translateZ(0); }
    .will-change-transform { will-change: transform; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6 mt-16"
         data-video-progress="{{ $progress && $progress->where('lesson_id', $lesson->id)->first() ? $progress->where('lesson_id', $lesson->id)->first()->video_progress : 0 }}"
         data-lesson-duration="{{ $lesson->duration * 60 }}"
         data-is-completed="{{ $progress && $progress->where('lesson_id', $lesson->id)->where('completed', true)->first() ? 'true' : 'false' }}"
         data-course-is-free="{{ $course->is_free ? 'true' : 'false' }}">
        
        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
            <!-- Optimized Course Sidebar -->
            <aside class="w-full lg:w-[280px] xl:w-[300px] flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm sticky top-4 gpu-accelerated">
                    <!-- Course Header -->    
                    <div class="p-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 lesson-title truncate">{{ $course->title }}</h2>
                        <p class="text-sm text-gray-500 mt-1 truncate">{{ $course->instructor->name }}</p>
                    </div>
                    
                    <!-- Lessons List -->
                    <div class="p-3 max-h-[calc(100vh-220px)] overflow-y-auto">
                        <div class="space-y-1">
                            @foreach($course->lessons as $index => $courseLesson)
                                <a href="{{ route('client.courses.learn', ['course' => $course->slug, 'lesson' => $courseLesson->id]) }}" 
                                   class="lesson-item lesson-link flex items-center p-3 rounded-lg hover:bg-gray-50 will-change-transform {{ $lesson && $lesson->id === $courseLesson->id ? 'bg-indigo-50 ring-1 ring-indigo-200' : '' }}"
                                   @if($index > 5) data-lazy-load @endif>
                                    
                                    <!-- Lesson Icon -->
                                    <div class="flex-shrink-0 mr-3">
                                        @if($courseLesson->video_url)
                                            <svg class="w-5 h-5 {{ $lesson && $lesson->id === $courseLesson->id ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 {{ $lesson && $lesson->id === $courseLesson->id ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    
                                    <!-- Lesson Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium {{ $lesson && $lesson->id === $courseLesson->id ? 'text-indigo-600' : 'text-gray-900' }} truncate">
                                            {{ $courseLesson->title }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $courseLesson->duration }} min</p>
                                    </div>
                                    
                                    <!-- Completion Badge -->
                                    @if($progress && $progress->where('lesson_id', $courseLesson->id)->where('completed', true)->first())
                                        <div class="flex-shrink-0 ml-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="mr-1 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Done
                                            </span>
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="w-full lg:flex-1">
                @if($lesson)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden gpu-accelerated">
                        <!-- Optimized Video Player -->
                        @if($lesson->video_url)
                            <div class="video-container relative w-full max-w-4xl mx-auto bg-black">
                                <div class="relative w-full aspect-[16/9]">
                                    
                                    @if(Str::startsWith($lesson->video_url, ['http://', 'https://']))
                                        {{-- External URL (YouTube, Vimeo, etc.) --}}
                                        @if(Str::contains($lesson->video_url, 'youtube.com/watch'))
                                            @php
                                                $videoId = null;
                                                parse_str(parse_url($lesson->video_url, PHP_URL_QUERY), $query);
                                                if (isset($query['v'])) {
                                                    $videoId = $query['v'];
                                                }
                                                $embedUrl = $videoId ? "https://www.youtube.com/embed/{$videoId}?enablejsapi=1&origin=" . request()->getSchemeAndHttpHost() : $lesson->video_url;
                                            @endphp
                                            <iframe 
                                                src="{{ $embedUrl }}" 
                                                class="absolute top-0 left-0 w-full h-full"
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen>
                                            </iframe>
                                        @elseif(Str::contains($lesson->video_url, 'youtu.be'))
                                            @php
                                                $videoId = basename(parse_url($lesson->video_url, PHP_URL_PATH));
                                                $embedUrl = "https://www.youtube.com/embed/{$videoId}?enablejsapi=1&origin=" . request()->getSchemeAndHttpHost();
                                            @endphp
                                            <iframe 
                                                src="{{ $embedUrl }}" 
                                                class="absolute top-0 left-0 w-full h-full"
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen>
                                            </iframe>
                                        @else
                                            <iframe 
                                                src="{{ $lesson->video_url }}" 
                                                class="absolute top-0 left-0 w-full h-full"
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen>
                                            </iframe>
                                        @endif
                                    @else
                                        {{-- Optimized uploaded video --}}
                                        <video 
                                            id="lessonVideo" 
                                            class="absolute top-0 left-0 w-full h-full object-contain" 
                                            controls 
                                            playsinline
                                            preload="metadata"
                                            crossorigin="anonymous">
                                            <source src="{{ $lesson->getDisplayVideoUrl() }}" type="video/mp4">
                                            <p class="text-white text-center p-4">
                                                Your browser does not support the video tag. 
                                                <a href="{{ $lesson->getDisplayVideoUrl() }}" class="text-blue-400 underline">Download the video</a>
                                            </p>
                                        </video>
                                    @endif
                                </div>
                            </div>

                            <!-- Optimized Progress Bar -->
                            <div class="progress-bar-container bg-gray-50 px-6 py-4 border-b border-gray-100">
                                <div class="relative">
                                    <div class="flex mb-2 items-center justify-between">
                                        <span class="text-xs font-semibold inline-block py-1 px-2.5 uppercase rounded-full text-indigo-600 bg-indigo-100">
                                            Progress
                                        </span>
                                        <span class="text-xs font-semibold inline-block text-indigo-600" id="progressPercentage">0%</span>
                                    </div>
                                    <div class="overflow-hidden h-2 mb-2 text-xs flex rounded-full bg-indigo-100">
                                        <div id="progressBar" style="width:0%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600 transition-all duration-300 rounded-full"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500">
                                        <span id="currentTime">0:00</span>
                                        <span id="totalTime">0:00</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Optimized Lesson Content -->
                        <div class="p-6 lg:p-8">
                            <!-- Lesson Header -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                                <h1 class="text-xl lg:text-2xl font-bold text-gray-900 lesson-title">{{ $lesson->title }}</h1>
                                @if($progress && $progress->where('lesson_id', $lesson->id)->where('completed', true)->first())
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Completed
                                    </span>
                                @endif
                            </div>

                            <!-- Lesson Description -->
                            <div class="prose prose-lg max-w-none mb-8">
                                {!! $lesson->description !!}
                            </div>

                            <!-- Completion Section -->
                            @php
                                $lessonProgress = $progress ? $progress->where('lesson_id', $lesson->id)->first() : null;
                                $isCompleted = $lessonProgress && $lessonProgress->completed;
                            @endphp
                            
                            @if($isCompleted)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                    <div class="flex items-center justify-center text-green-700">
                                        <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="text-center">
                                            <h3 class="text-lg font-semibold">Lesson Completed!</h3>
                                            <p class="text-sm text-green-600 mt-1">You can rewatch this lesson anytime</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button type="button" 
                                        id="markCompleteBtn"
                                        onclick="markAsComplete()"
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-lg text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 gpu-accelerated">
                                    <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Mark as Complete
                                </button>
                            @endif

                            <!-- Lazy-loaded Assignments Section -->
                            @if(!$course->is_free && $lesson->assignments && $lesson->assignments->count() > 0)
                                <div id="assignmentsSection" class="border-t border-gray-200 mt-8 pt-8" style="display: none;">
                                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Assignments</h2>
                                    <div class="space-y-4">
                                        @foreach($lesson->assignments as $assignment)
                                            <div class="bg-gray-50 rounded-xl p-6">
                                                <h3 class="text-lg font-medium text-gray-900 mb-3">{{ $assignment->title }}</h3>
                                                <p class="text-gray-600 mb-4">{{ $assignment->description }}</p>
                                                
                                                @php
                                                    $submission = $submissions->where('assignment_id', $assignment->id)->first();
                                                @endphp

                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                    <div>
                                                        <span class="text-sm text-gray-500">Due: {{ $assignment->due_date->format('M d, Y') }}</span>
                                                        @if($submission)
                                                            <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $submission->status === 'submitted' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                {{ ucfirst($submission->status) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if(!$submission)
                                                        <a href="{{ route('client.assignments.show', $assignment->id) }}" 
                                                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                                                            Start Assignment
                                                        </a>
                                                    @else
                                                        <a href="{{ route('client.assignments.show', $assignment->id) }}" 
                                                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                                            View Submission
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Course Completion Alert -->
                            @if($isCourseCompleted)
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mt-8">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-green-900 mb-2">🎉 Congratulations!</h3>
                                            <p class="text-green-800 mb-4">You have completed all lessons in this course!</p>
                                            
                                            @if(!$hasReviewed)
                                                <div class="flex flex-col sm:flex-row gap-3">
                                                    <a href="{{ route('client.courses.show', $course->slug) }}#reviews" 
                                                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                        </svg>
                                                        Write a Review
                                                    </a>
                                                    <a href="{{ route('client.enrollments.index') }}" 
                                                       class="inline-flex items-center justify-center px-4 py-2 border border-green-600 text-sm font-medium rounded-lg text-green-600 bg-transparent hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                        </svg>
                                                        My Learning
                                                    </a>
                                                </div>
                                            @else
                                                <div class="flex items-center text-green-700">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium">Thank you for your review!</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-3">Welcome to {{ $course->title }}</h2>
                        <p class="text-gray-600 text-lg">Select a lesson from the sidebar to begin learning.</p>
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>

<!-- Optimized Modals -->
<div id="successModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-sm w-full mx-4 shadow-xl transform transition-all gpu-accelerated">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-100 mb-5">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Lesson Completed!</h3>
            <p class="text-base text-gray-600">You have successfully completed this lesson.</p>
        </div>
    </div>
</div>

<div id="warningModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-sm w-full mx-4 shadow-xl transform transition-all gpu-accelerated">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-yellow-100 mb-5">
                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Skip Limit Reached</h3>
            <p class="text-base text-gray-600">You cannot skip video content.</p>
            <button onclick="hideWarningModal()" class="mt-6 w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                OK
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Optimized JavaScript for learning page
let youtubePlayer = null;
let youtubeProgressInterval = null;
let isAutoCompleting = false;
let hasWatchedEntireVideo = false;
let video = null;
let lastValidTime = 0;
let skipAttempts = 0;
const maxSkipAttempts = 3;
const maxSeekAhead = 120;
let isCompleted = false;

// Performance optimizations
const throttle = (func, delay) => {
    let timeoutId;
    let lastExecTime = 0;
    return function (...args) {
        const currentTime = Date.now();
        if (currentTime - lastExecTime > delay) {
            func.apply(this, args);
            lastExecTime = currentTime;
        } else {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                func.apply(this, args);
                lastExecTime = Date.now();
            }, delay - (currentTime - lastExecTime));
        }
    };
};

const debounce = (func, delay) => {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
};

document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-video-progress]');
    const videoProgress = parseInt(container.dataset.videoProgress) || 0;
    const lessonDuration = parseInt(container.dataset.lessonDuration) || 0;
    isCompleted = container.dataset.isCompleted === 'true';
    
    // Initialize completion state
    if (isCompleted) {
        initializeCompletedLesson();
        updateProgressDisplay(true);
    }
    
    // Set initial progress immediately
    setInitialProgress(videoProgress, lessonDuration);
    
    // Initialize video player
    video = document.getElementById('lessonVideo');
    initializeVideoPlayer();
    
    // Optimized progress update - throttled to every 2 seconds
    const throttledProgressUpdate = throttle(function() {
        if (video && video.currentTime > 0) {
            const currentTime = video.currentTime;
            let duration = video.duration || lessonDuration;
            
            if (duration && duration > 0) {
                updateVideoProgress(currentTime, duration);
            } else {
                const currentTimeElement = document.getElementById('currentTime');
                if (currentTimeElement) {
                    currentTimeElement.textContent = formatTime(currentTime);
                }
            }
        }
    }, 2000);
    
    setInterval(throttledProgressUpdate, 2000);
});

function setInitialProgress(videoProgress, lessonDuration) {
    const progressBar = document.getElementById('progressBar');
    const progressPercentage = document.getElementById('progressPercentage');
    const currentTimeElement = document.getElementById('currentTime');
    const totalTimeElement = document.getElementById('totalTime');
    
    if (isCompleted) {
        if (progressBar) progressBar.style.width = '100%';
        if (progressPercentage) progressPercentage.textContent = '100%';
        if (currentTimeElement && lessonDuration > 0) {
            currentTimeElement.textContent = formatTime(lessonDuration);
        }
        if (totalTimeElement && lessonDuration > 0) {
            totalTimeElement.textContent = formatTime(lessonDuration);
        }
    } else if (videoProgress > 0 && lessonDuration > 0) {
        const initialPercentage = Math.min((videoProgress / lessonDuration) * 100, 100);
        if (progressBar) progressBar.style.width = `${initialPercentage}%`;
        if (progressPercentage) progressPercentage.textContent = `${Math.round(initialPercentage)}%`;
        if (currentTimeElement) currentTimeElement.textContent = formatTime(videoProgress);
        if (totalTimeElement) totalTimeElement.textContent = formatTime(lessonDuration);
    }
    
    if (isCompleted || videoProgress >= lessonDuration) {
        hasWatchedEntireVideo = true;
        showAssignments();
    }
}

function initializeVideoPlayer() {
    if (video) {
        setupLocalVideo();
    } else {
        setupYoutubeVideo();
    }
}

function setupLocalVideo() {
    const throttledTimeUpdate = throttle(function() {
        const currentTime = video.currentTime;
        const skipDistance = currentTime - lastValidTime;
        
        if (video.duration && !isNaN(video.duration) && video.duration > 0) {
            updateVideoProgress(currentTime, video.duration);
        }
        
        // Skip validation
        if (skipDistance > maxSeekAhead && !hasWatchedEntireVideo) {
            skipAttempts++;
            if (skipAttempts >= maxSkipAttempts) {
                video.currentTime = lastValidTime;
                showWarningModal('You have attempted to skip multiple times. Please watch the course content in order.');
                skipAttempts = 0;
            } else {
                video.currentTime = lastValidTime;
                showWarningModal(`Please do not skip video content. ${maxSkipAttempts - skipAttempts} warnings remaining.`);
            }
        } else if (skipDistance <= maxSeekAhead || hasWatchedEntireVideo) {
            lastValidTime = currentTime;
            skipAttempts = Math.max(0, skipAttempts - 1);
        }
        
        // Throttled progress save
        debouncedSaveProgress(currentTime);
    }, 1000);

    // Optimized event listeners
    video.addEventListener('loadedmetadata', function() {
        if (video.duration && !isNaN(video.duration) && video.duration > 0) {
            updateVideoProgress(video.currentTime, video.duration);
            document.getElementById('totalTime').textContent = formatTime(video.duration);
        }
        lastValidTime = video.currentTime;
    });

    video.addEventListener('timeupdate', throttledTimeUpdate);

    video.addEventListener('ended', function() {
        hasWatchedEntireVideo = true;
        updateVideoProgress(video.duration, video.duration);
        showAssignments();
    });

    video.addEventListener('ratechange', function() {
        if (video.playbackRate > 2) {
            video.playbackRate = 2;
            showWarningModal('Playback speed cannot exceed 2x.');
        }
    });
    
    // Initialize progress from database
    video.addEventListener('loadstart', function() {
        const container = document.querySelector('[data-video-progress]');
        const videoProgress = parseInt(container.dataset.videoProgress) || 0;
        if (videoProgress > 0) {
            video.currentTime = videoProgress;
        }
    });
}

function setupYoutubeVideo() {
    const iframe = document.querySelector('iframe');
    if (!iframe || !iframe.src.includes('youtube.com')) return;

    if (!window.YT) {
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        tag.onload = () => {
            if (window.YT && window.YT.Player) {
                initYouTubePlayer(iframe);
            }
        };
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    } else {
        initYouTubePlayer(iframe);
    }
}

function initYouTubePlayer(iframe) {
    iframe.id = 'youtubePlayer';
    
    window.onYouTubeIframeAPIReady = function() {
        youtubePlayer = new YT.Player('youtubePlayer', {
            events: {
                'onReady': onYoutubeReady,
                'onStateChange': onYoutubeStateChange,
                'onPlaybackRateChange': onYoutubePlaybackRateChange
            }
        });
    };
}

function onYoutubeReady(event) {
    const container = document.querySelector('[data-video-progress]');
    const videoProgress = parseInt(container.dataset.videoProgress) || 0;
    
    if (videoProgress > 0) {
        event.target.seekTo(videoProgress, true);
    }

    if (youtubeProgressInterval) {
        clearInterval(youtubeProgressInterval);
    }
    youtubeProgressInterval = setInterval(updateYoutubeProgress, 2000); // Reduced frequency
    event.target.setPlaybackRate(1);
}

function onYoutubeStateChange(event) {
    if (event.data === YT.PlayerState.ENDED) {
        hasWatchedEntireVideo = true;
        if (youtubePlayer && typeof youtubePlayer.getDuration === 'function') {
            const duration = youtubePlayer.getDuration();
            updateVideoProgress(duration, duration);
        }
        showAssignments();
        if (youtubeProgressInterval) {
            clearInterval(youtubeProgressInterval);
        }
    }
}

function onYoutubePlaybackRateChange(event) {
    if (youtubePlayer && typeof youtubePlayer.getPlaybackRate === 'function') {
        const rate = youtubePlayer.getPlaybackRate();
        if (rate > 2) {
            youtubePlayer.setPlaybackRate(2);
            showWarningModal('Playback speed cannot exceed 2x.');
        }
    }
    
    if (youtubePlayer && typeof youtubePlayer.getCurrentTime === 'function') {
        lastValidTime = youtubePlayer.getCurrentTime();
    }
}

const updateYoutubeProgress = throttle(function() {
    if (!youtubePlayer || typeof youtubePlayer.getCurrentTime !== 'function') {
        return;
    }

    try {
        const currentTime = youtubePlayer.getCurrentTime();
        const skipDistance = currentTime - lastValidTime;
        
        if (skipDistance > maxSeekAhead && !hasWatchedEntireVideo) {
            skipAttempts++;
            if (skipAttempts >= maxSkipAttempts) {
                youtubePlayer.seekTo(lastValidTime, true);
                showWarningModal('You have attempted to skip multiple times. Please watch the course content in order.');
                skipAttempts = 0;
            } else {
                youtubePlayer.seekTo(lastValidTime, true);
                showWarningModal(`Please do not skip video content. ${maxSkipAttempts - skipAttempts} warnings remaining.`);
            }
        } else if (skipDistance <= maxSeekAhead || hasWatchedEntireVideo) {
            lastValidTime = currentTime;
            skipAttempts = Math.max(0, skipAttempts - 1);
        }

        const duration = youtubePlayer.getDuration();
        if (!isNaN(currentTime) && !isNaN(duration) && duration > 0) {
            updateVideoProgress(currentTime, duration);
            debouncedSaveProgress(currentTime);
        }
    } catch (error) {
        console.error('Error updating YouTube progress:', error);
    }
}, 2000);

function updateVideoProgress(currentTime, duration) {
    if (isCompleted) return;
    
    if (typeof currentTime !== 'number' || isNaN(currentTime)) currentTime = 0;
    if (typeof duration !== 'number' || isNaN(duration) || duration <= 0) {
        if (video && video.duration && !isNaN(video.duration)) {
            duration = video.duration;
        } else if (youtubePlayer && typeof youtubePlayer.getDuration === 'function') {
            duration = youtubePlayer.getDuration();
        } else {
            const currentTimeElement = document.getElementById('currentTime');
            if (currentTimeElement) currentTimeElement.textContent = formatTime(currentTime);
            return;
        }
    }
    
    const percentage = Math.min((currentTime / duration) * 100, 100);
    
    // Batch DOM updates
    requestAnimationFrame(() => {
        const progressBar = document.getElementById('progressBar');
        const progressPercentage = document.getElementById('progressPercentage');
        const currentTimeElement = document.getElementById('currentTime');
        const totalTimeElement = document.getElementById('totalTime');
        
        if (progressBar) progressBar.style.width = `${percentage}%`;
        if (progressPercentage) progressPercentage.textContent = `${Math.round(percentage)}%`;
        if (currentTimeElement) currentTimeElement.textContent = formatTime(currentTime);
        if (totalTimeElement) totalTimeElement.textContent = formatTime(duration);
    });
    
    if (percentage >= 95) {
        hasWatchedEntireVideo = true;
        showAssignments();
    }
}

function formatTime(seconds) {
    if (!seconds || seconds < 0) return '0:00';
    const minutes = Math.floor(seconds / 60);
    seconds = Math.floor(seconds % 60);
    return `${minutes}:${seconds.toString().padStart(2, '0')}`;
}

// Debounced save progress to reduce server requests
const debouncedSaveProgress = debounce(function(currentTime) {
    if (isCompleted || typeof currentTime !== 'number' || isNaN(currentTime) || currentTime < 0) {
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('video_progress', Math.floor(currentTime));

    fetch('{{ route("client.lessons.progress", ["course" => $course->slug, "lesson" => $lesson->id]) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .catch(error => console.error('Error saving progress:', error));
}, 3000);

function showAssignments() {
    const isFree = document.querySelector('[data-course-is-free]').dataset.courseIsFree === 'true';
    if (!isFree) {
        const assignmentsSection = document.getElementById('assignmentsSection');
        if (assignmentsSection) {
            assignmentsSection.style.display = 'block';
        }
    }
}

function showSuccessModal() {
    const modal = document.getElementById('successModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    updateCompletionUI();
    
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        window.location.reload();
    }, 3000);
}

function updateCompletionUI() {
    isCompleted = true;
    updateProgressDisplay(true);
    
    const markCompleteBtn = document.getElementById('markCompleteBtn');
    if (markCompleteBtn) {
        const completedHTML = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center justify-center text-green-700">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold">Lesson Completed!</h3>
                        <p class="text-sm text-green-600 mt-1">You can rewatch this lesson anytime</p>
                    </div>
                </div>
            </div>
        `;
        markCompleteBtn.outerHTML = completedHTML;
    }
}

function initializeCompletedLesson() {
    hasWatchedEntireVideo = true;
    updateProgressDisplay(true);
}

function updateProgressDisplay(isCompleted) {
    if (isCompleted) {
        requestAnimationFrame(() => {
            const progressBar = document.getElementById('progressBar');
            const progressPercentage = document.getElementById('progressPercentage');
            
            if (progressBar) progressBar.style.width = '100%';
            if (progressPercentage) progressPercentage.textContent = '100%';
        });
    }
}

function markAsComplete() {
    if (isAutoCompleting) return;
    isAutoCompleting = true;

    let currentProgress = 0;
    let duration = 0;
    
    try {
        if (video && !isNaN(video.currentTime) && !isNaN(video.duration)) {
            currentProgress = Math.floor(video.currentTime);
            duration = Math.floor(video.duration);
        } else if (youtubePlayer && typeof youtubePlayer.getCurrentTime === 'function') {
            currentProgress = Math.floor(youtubePlayer.getCurrentTime());
            duration = Math.floor(youtubePlayer.getDuration());
        }
    } catch (error) {
        console.error('Error getting current time:', error);
    }

    if (duration <= 0) {
        currentProgress = Math.max(60, currentProgress);
    } else {
        const progressPercentage = (currentProgress / duration) * 100;
        if (progressPercentage < 70 && !hasWatchedEntireVideo && currentProgress < 60) {
            alert('You must watch more of the video before marking it as complete. Please watch at least 70% or 60 seconds minimum.');
            isAutoCompleting = false;
            return;
        }
    }
    
    const finalProgress = Math.max(60, currentProgress);
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('video_progress', finalProgress);
    formData.append('completed', 'true');

    fetch('{{ route("client.lessons.progress", ["course" => $course->slug, "lesson" => $lesson->id]) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal();
        } else {
            alert(data.message || 'Failed to mark lesson as complete');
            isAutoCompleting = false;
        }
    })
    .catch(error => {
        console.error('Error marking lesson as complete:', error);
        alert('Failed to mark lesson as complete. Please try again.');
        isAutoCompleting = false;
    });
}

function showWarningModal(message = 'You cannot skip video content.') {
    const modal = document.getElementById('warningModal');
    if (modal) {
        const messageElement = modal.querySelector('p.text-gray-600');
        if (messageElement) {
            messageElement.textContent = message;
        }
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideWarningModal();
            }
        });
    }
}

function hideWarningModal() {
    const modal = document.getElementById('warningModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}
</script>
@endpush

@endsection