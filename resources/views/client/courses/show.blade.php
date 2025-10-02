@extends('layouts.client')

@section('title', $course->title)

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    </div>
@endif

<div class="bg-white shadow rounded-lg overflow-hidden">
    <!-- Course Header -->
    <div class="relative">
        @if($course->thumbnail)
            <img src="{{ filter_var($course->thumbnail, FILTER_VALIDATE_URL) ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}" 
                 alt="{{ $course->title }}" 
                 class="w-full h-64 object-cover"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';">
        @else
            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
        @endif
    </div>

    <div class="p-8">
        <!-- Course Info -->
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center space-x-2 mb-4">
                <span class="px-3 py-1 text-sm font-medium bg-indigo-100 text-indigo-800 rounded-full">
                    {{ $course->category->name }}
                </span>
                <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                    {{ $course->level_label }}
                </span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>
            
            <!-- Course Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-1">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="ml-1 text-lg font-semibold text-gray-900">{{ number_format($course->average_rating, 1) }}</span>
                        <span class="ml-1 text-sm text-gray-500">({{ $course->total_ratings }})</span>
                    </div>
                    <p class="text-sm text-gray-600">Course Rating</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-1">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="ml-1 text-lg font-semibold text-gray-900">{{ $course->learning_hours }}</span>
                    </div>
                    <p class="text-sm text-gray-600">Learning Hours</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-1">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="ml-1 text-lg font-semibold text-gray-900">{{ $course->enrollment_count }}</span>
                    </div>
                    <p class="text-sm text-gray-600">Enrolled Students</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-1">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="ml-1 text-lg font-semibold text-gray-900">{{ $course->instructor->name }}</span>
                    </div>
                    <p class="text-sm text-gray-600">Instructor</p>
                </div>
            </div>

            <!-- Course Description -->
            <div class="prose max-w-none mb-8">
                <h2 class="text-xl font-semibold mb-4">About This Course</h2>
                {{ $course->description }}
            </div>

            <!-- Course Instructor -->
            <div class="border-t border-gray-200 pt-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Course Instructor</h2>
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <img class="h-24 w-24 rounded-full object-cover" 
                                 src="{{ $course->instructor->profile_picture 
                                    ? asset('storage/' . $course->instructor->profile_picture) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) . '&size=96&background=random' }}" 
                                 alt="{{ $course->instructor->name }}">
                        </div>
                        <div class="ml-6">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $course->instructor->name }}</h3>
                            @if($course->instructor->qualification)
                                <p class="text-indigo-600 font-medium mt-1">{{ $course->instructor->qualification }}</p>
                            @endif
                            @if($course->instructor->bio)
                                <p class="text-gray-700 mt-3">{{ $course->instructor->bio }}</p>
                            @endif
                            <div class="mt-4 flex items-center space-x-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span class="ml-2 text-gray-600">{{ $course->instructor->courses_count ?? 0 }} Courses</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="ml-2 text-gray-600">{{ $course->instructor->students_count ?? 0 }} Students</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills You'll Learn -->
            @if($course->skills_to_learn)
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Skills You'll Learn</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($course->skills_to_learn as $skill)
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-700">{{ $skill }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Enrollment Section -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center justify-between">
                    <div>
                        @if($course->is_free)
                            <span class="text-3xl font-bold text-green-600">Free</span>
                            <span class="text-gray-500 text-sm ml-2">No payment required</span>
                        @else
                            <span class="text-3xl font-bold text-gray-900">RM{{ number_format($course->price, 2) }}</span>
                            <span class="text-gray-500 text-sm ml-2">One-time payment</span>
                        @endif
                    </div>
                    <div>
                        @auth
                            @php
                                $isEnrolled = auth()->user()->enrollments()
                                    ->where('course_id', $course->id)
                                    ->where('payment_status', 'completed')
                                    ->exists();
                            @endphp
                            
                            @if($isEnrolled)
                                <a href="{{ route('client.courses.learn', $course->slug) }}" 
                                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Continue Learning
                                </a>
                            @else
                                @if($course->is_free)
                                    <form action="{{ route('client.enrollments.store', $course) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Start Free Course
                                        </button>
                                    </form>
                                @else
                                    <!-- Payment Confirmation Button -->
                                    <a href="{{ route('client.paypal.confirm', $course) }}" 
                                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                        </svg>
                                        Enroll Now - RM{{ number_format($course->price, 2) }}
                                    </a>
                                    
                                    <!-- Alternative Direct Enrollment (for testing or other payment methods) -->
                                    <div class="mt-3">
                                        <p class="text-sm text-gray-500 text-center">
                                            Having trouble with PayPal? 
                                            <a href="#" onclick="showDirectEnrollment()" class="text-indigo-600 hover:text-indigo-500">
                                                Contact support
                                            </a>
                                        </p>
                                    </div>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Login to Enroll
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Course Content -->
            @if($course->lessons->count() > 0)
                <div class="border-t border-gray-200 pt-8 mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Course Content</h2>
                    <div class="space-y-4">
                        @foreach($course->lessons as $lesson)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-gray-700">{{ $lesson->title }}</span>
                                </div>
                                <span class="text-gray-500 text-sm">{{ $lesson->duration }} min</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Course Review Section (for completed courses) -->
            @auth
                @if($canReview)
                    <div class="border-t border-gray-200 pt-8 mt-8">
                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h2 class="text-2xl font-bold text-gray-900">Congratulations! You've completed this course</h2>
                            </div>
                            <p class="text-gray-600 mb-6">Share your experience to help other students make informed decisions about this course.</p>
                        </div>
                        @include('client.courses._review_form')
                    </div>
                @elseif($existingReview)
                    <div class="border-t border-gray-200 pt-8 mt-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Your Review</h2>
                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-3">
                                        <div class="flex items-center mr-4">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $existingReview->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500">Reviewed {{ $existingReview->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($existingReview->comment)
                                        <p class="text-gray-700">{{ $existingReview->comment }}</p>
                                    @else
                                        <p class="text-gray-500 italic">No written review provided</p>
                                    @endif
                                </div>
                                <div class="flex items-center text-blue-600 ml-4">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-sm font-medium">Thank you for your review!</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($hasCompletedCourse && !$canReview)
                    <div class="border-t border-gray-200 pt-8 mt-8">
                        <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Course Completed!</h3>
                                    <p class="text-gray-600 mt-1">You have successfully completed this course. You already submitted a review for this course.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($isEnrolled && !$hasCompletedCourse)
                    <div class="border-t border-gray-200 pt-8 mt-8">
                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Complete the course to leave a review</h3>
                                    <p class="text-gray-600 mt-1">Finish all lessons to share your experience and help other students.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Reviews Section -->
            <div class="border-t border-gray-200 pt-8 mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Student Reviews</h2>
                
                @if($reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random" 
                                             alt="{{ $review->user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $review->user->name }}</h4>
                                        <div class="flex items-center mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($review->comment)
                                            <p class="mt-3 text-gray-700">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $reviews->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Be the first to review this course!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDirectEnrollment() {
    alert('For payment issues, please contact our support team at support@example.com or call 1-800-123-4567');
}
</script>
@endpush
@endsection 