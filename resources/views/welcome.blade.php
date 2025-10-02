@extends('layouts.client')

@section('title', 'Online Course Platform - Learn Anywhere, Anytime')

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    }
    
    .animate-slow-zoom {
        animation: slowZoom 20s infinite alternate;
    }
    
    @keyframes slowZoom {
        from { transform: scale(1); }
        to { transform: scale(1.1); }
    }

    .feature-card {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stats-card {
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .course-card {
        transition: all 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
    }

    .instructor-card {
        transition: all 0.3s ease;
    }

    .instructor-card:hover {
        transform: translateY(-5px);
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* CSS-only animations to replace AOS */
    .fade-in {
        opacity: 0;
        animation: fadeIn 1s ease-out forwards;
    }
    
    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 1s ease-out forwards;
    }
    
    .fade-in-right {
        opacity: 0;
        transform: translateX(-30px);
        animation: fadeInRight 1s ease-out forwards;
    }
    
    .fade-in-left {
        opacity: 0;
        transform: translateX(30px);
        animation: fadeInLeft 1s ease-out forwards;
    }
    
    .fade-in-delay-100 {
        animation-delay: 0.1s;
    }
    
    .fade-in-delay-200 {
        animation-delay: 0.2s;
    }
    
    .fade-in-delay-300 {
        animation-delay: 0.3s;
    }
    
    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInRight {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeInLeft {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[80vh] flex items-center justify-center overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/courses/web/laravel-vue-course.jpg') }}" alt="Hero Background" class="w-full h-full object-cover filter brightness-[0.6]">
        </div>

        <!-- Animated Background Pattern -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-black/30 z-10"></div>
            <div class="absolute inset-0 bg-grid-white/[0.08] bg-[size:60px_60px] z-0"></div>
        </div>

        <!-- Main Content -->
        <div class="relative z-20 container mx-auto px-6 sm:px-8 lg:px-12 py-24 md:py-32">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left Column -->
                <div class="text-center lg:text-left space-y-8 fade-in-right">
                    <!-- Welcome Badge -->
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 transform hover:scale-105 transition-all duration-300">
                        <span class="text-sm font-medium text-white/90">Welcome to LearnHub</span>
                        <svg class="ml-2 h-4 w-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight">
                        <span class="block text-white mb-3">Transform Your Future</span>
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-purple-200 to-indigo-200">
                            Through Quality Education
                        </span>
                    </h1>

                    <!-- Description -->
                    <p class="text-base sm:text-lg text-white/80 max-w-2xl leading-relaxed mt-4">
                        Join thousands of learners worldwide. Access premium courses, learn from industry experts, and advance your career.
                    </p>
                </div>

                <!-- Right Column - Feature Card -->
                <div class="lg:ml-auto fade-in-left fade-in-delay-200">
                    <div class="feature-card rounded-2xl p-8 max-w-md mx-auto backdrop-blur-lg bg-white/[0.08] border border-white/[0.12] shadow-xl">
                        <div class="flex items-center space-x-4 mb-8">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Why Choose LearnHub?</h3>
                                <p class="text-white/80 mt-1">Join our community of learners</p>
                            </div>
                        </div>

                        <!-- Feature List -->
                        <ul class="space-y-6">
                            <li class="flex items-start space-x-4 group">
                                <div class="flex-shrink-0 h-6 w-6 rounded-full bg-indigo-500/20 flex items-center justify-center mt-1">
                                    <svg class="h-4 w-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-base text-white/90 group-hover:text-white transition-colors duration-200">
                                    Access to 1000+ premium courses
                                </span>
                            </li>
                            <li class="flex items-start space-x-4 group">
                                <div class="flex-shrink-0 h-6 w-6 rounded-full bg-indigo-500/20 flex items-center justify-center mt-1">
                                    <svg class="h-4 w-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-base text-white/90 group-hover:text-white transition-colors duration-200">
                                    Learn from industry experts
                                </span>
                            </li>
                            <li class="flex items-start space-x-4 group">
                                <div class="flex-shrink-0 h-6 w-6 rounded-full bg-indigo-500/20 flex items-center justify-center mt-1">
                                    <svg class="h-4 w-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-base text-white/90 group-hover:text-white transition-colors duration-200">
                                    Flexible learning schedule
                                </span>
                            </li>
                            <li class="flex items-start space-x-4 group">
                                <div class="flex-shrink-0 h-6 w-6 rounded-full bg-indigo-500/20 flex items-center justify-center mt-1">
                                    <svg class="h-4 w-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-base text-white/90 group-hover:text-white transition-colors duration-200">
                                    Certificate upon completion
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <!-- Stats Cards -->
                <div class="stats-card p-4 rounded-lg bg-white border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 fade-in-up">
                    <div class="text-2xl md:text-3xl font-bold text-indigo-600 mb-1">1000+</div>
                    <div class="text-sm text-gray-600">Active Courses</div>
                </div>
                
                <div class="stats-card p-4 rounded-lg bg-white border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 fade-in-up fade-in-delay-100">
                    <div class="text-2xl md:text-3xl font-bold text-indigo-600 mb-1">50K+</div>
                    <div class="text-sm text-gray-600">Happy Students</div>
                </div>
                
                <div class="stats-card p-4 rounded-lg bg-white border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 fade-in-up fade-in-delay-200">
                    <div class="text-2xl md:text-3xl font-bold text-indigo-600 mb-1">200+</div>
                    <div class="text-sm text-gray-600">Expert Instructors</div>
                </div>
                
                <div class="stats-card p-4 rounded-lg bg-white border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 fade-in-up fade-in-delay-300">
                    <div class="text-2xl md:text-3xl font-bold text-indigo-600 mb-1">95%</div>
                    <div class="text-sm text-gray-600">Success Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trending Now Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900">Trending Now</h2>
            </div>

            <!-- Course Slider Container -->
            <div class="relative">
                <!-- Navigation Arrows -->
                <button data-slider-prev class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 p-2 rounded-full bg-white shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button data-slider-next class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 p-2 rounded-full bg-white shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Course Cards Container -->
                <div class="overflow-hidden">
                    <div class="flex gap-6 overflow-x-auto pb-4 snap-x snap-mandatory hide-scrollbar">
                        @forelse($trendingCourses as $course)
                        <div class="flex-none w-[320px] snap-start">
                            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 h-[480px] flex flex-col">
                                <!-- Course Image -->
                                <div class="relative h-[200px] flex-shrink-0">
                                    <!-- Free/Paid Badge -->
                                    <div class="absolute top-3 left-3 z-10">
                                        @if($course->is_free || $course->price == 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                FREE
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800">
                                                RM{{ number_format($course->price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Course Thumbnail -->
                                    @php
                                        $thumbnailSrc = null;
                                        if ($course->thumbnail) {
                                            $thumbnailSrc = filter_var($course->thumbnail, FILTER_VALIDATE_URL) 
                                                ? $course->thumbnail 
                                                : asset('storage/' . $course->thumbnail);
                                        }
                                    @endphp
                                    <img src="{{ $thumbnailSrc ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' }}" 
                                         alt="{{ $course->title }}" 
                                         class="w-full h-full object-cover"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';"
                                         onload="this.style.opacity='1';"
                                         style="opacity: 0; transition: opacity 0.3s ease;">
                                </div>

                                <!-- Course Info -->
                                <div class="p-5 flex-1 flex flex-col">
                                    <!-- Category -->
                                    @if($course->category)
                                    <div class="mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $course->category->name }}
                                        </span>
                                    </div>
                                    @endif

                                    <!-- Course Title -->
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2 h-[56px] flex items-start">
                                        {{ $course->title }}
                                    </h3>

                                    <!-- Instructor -->
                                    @if($course->teacher)
                                    <p class="text-sm text-gray-600 mb-3 h-[20px]">
                                        By {{ $course->teacher->name }}
                                    </p>
                                    @endif

                                    <!-- Rating and Stats -->
                                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4 h-[20px]">
                                        @if($course->average_rating > 0)
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span>{{ number_format($course->average_rating, 1) }}</span>
                                        </div>
                                        <span class="text-gray-400">•</span>
                                        @endif
                                        <span>{{ $course->enrollments_count ?? 0 }} enrolled</span>
                                        @if($course->learning_hours)
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $course->learning_hours }} hrs</span>
                                        @elseif($course->duration)
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $course->duration }}</span>
                                        @endif
                                    </div>

                                    <!-- Action Button -->
                                    <div class="flex items-center justify-between mt-auto">
                                        <a href="{{ route('client.courses.show', $course->slug) }}" 
                                           class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium text-sm group">
                                            View Course
                                            <svg class="ml-1 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                        
                                        @if(!$course->is_free && $course->price > 0)
                                        <span class="text-lg font-bold text-gray-900">
                                            RM{{ number_format($course->price, 2) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <!-- No courses message -->
                        <div class="flex-none w-full">
                            <div class="bg-white rounded-xl overflow-hidden shadow-sm p-8 text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No trending courses available</h3>
                                <p class="text-gray-600">Check back later for popular courses.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 opacity-50"></div>
        <div class="absolute inset-0 bg-grid-indigo/[0.03] bg-[size:20px_20px]"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
                <h2 class="text-sm font-semibold text-indigo-600 tracking-wide uppercase">Features</h2>
                <p class="mt-2 text-3xl sm:text-4xl font-extrabold text-gray-900">
                    Everything you need to succeed
                </p>
                <p class="mt-4 text-lg text-gray-600">
                    Our platform provides all the tools and resources you need to excel in your learning journey
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 xl:gap-12">
                <!-- Feature 1: Learn Online -->
                <div class="feature-card group relative bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 fade-in-up">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-purple-50/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <!-- Icon -->
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white mb-6 transform group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <!-- Content -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors duration-200">
                            Learn Online
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Access course content anytime, anywhere. Learn at your own pace with our flexible online platform.
                        </p>

                        <!-- Feature List -->
                        <ul class="mt-6 space-y-3">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                24/7 Access to Content
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                HD Video Lessons
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Downloadable Resources
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Feature 2: Expert Instructors -->
                <div class="feature-card group relative bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 fade-in-up fade-in-delay-100">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-purple-50/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <!-- Icon -->
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white mb-6 transform group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>

                        <!-- Content -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors duration-200">
                            Expert Instructors
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Learn from industry experts and experienced professionals in your field of interest.
                        </p>

                        <!-- Feature List -->
                        <ul class="mt-6 space-y-3">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Industry Veterans
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Real-world Experience
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Personalized Support
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Feature 3: Track Progress -->
                <div class="feature-card group relative bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 fade-in-up fade-in-delay-200">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-purple-50/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <!-- Icon -->
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white mb-6 transform group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <!-- Content -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors duration-200">
                            Track Progress
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Monitor your learning progress and earn certificates upon course completion.
                        </p>

                        <!-- Feature List -->
                        <ul class="mt-6 space-y-3">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Progress Dashboard
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Achievement Badges
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Course Certificates
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Instructors Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 fade-in-up">
                <h2 class="text-sm font-semibold text-indigo-600 tracking-wide uppercase">Meet Our Instructors</h2>
                <p class="mt-2 text-3xl sm:text-4xl font-extrabold text-gray-900">
                    Learn from Industry Experts
                </p>
                <p class="mt-4 text-lg text-gray-500">
                    Our instructors bring years of real-world experience to help you succeed in your learning journey
                </p>
            </div>

            @php
                $displayed = $instructors->take(3);
                $count = $displayed->count();
                $grid = $count === 1 ? 'flex justify-center' : ($count === 2 ? 'flex justify-center space-x-6' : 'grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3');
            @endphp
            <div class="{{ $grid }}">
                @foreach($displayed as $i => $instructor)
                    <div class="group bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 fade-in-up"
                        @if($count === 2)
                            style="width: 280px;"
                        @elseif($count === 1)
                            style="width: 320px;"
                        @endif
                    >
                        <div class="relative h-[200px] sm:h-[220px] overflow-hidden flex items-center justify-center">
                            @php
                                $imgSrc = $instructor->profile_picture ? asset('storage/' . $instructor->profile_picture) : null;
                            @endphp
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 src="{{ $imgSrc ?? 'https://ui-avatars.com/api/?name=' . urlencode($instructor->name) . '&size=200&background=random' }}"
                                 alt="{{ $instructor->name }}"
                                 loading="lazy"
                                 onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($instructor->name) }}&size=200&background=random';">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200">{{ $instructor->name }}</h3>
                            <p class="text-indigo-600 text-sm font-medium mb-2">{{ $instructor->qualification ?? 'Instructor' }}</p>
                            <p class="text-gray-600 text-xs mb-3 line-clamp-2">
                                {{ $instructor->bio }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500">{{ $instructor->courses_count }} Courses</span>
                                </div>
                                <a href="{{ route('client.courses.index', ['instructor' => $instructor->id]) }}"
                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-700 text-xs font-medium group">
                                    View Courses
                                    <svg class="ml-1 h-3 w-3 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="py-12 bg-white">
        <div class="px-4 lg:px-6">
            <div class="lg:text-center mb-8 fade-in-up">
                <h2 class="text-xs text-indigo-600 font-semibold tracking-wide uppercase">About Us</h2>
                <p class="mt-2 text-2xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-3xl">
                    Our Story
                </p>
                <p class="mt-4 max-w-2xl text-base text-gray-500 lg:mx-auto">
                    Empowering learners worldwide with quality education since 2020
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 items-center">
                <div class="relative fade-in-right">
                    <div class="relative h-[400px] rounded-lg overflow-hidden shadow-lg">
                        <img class="w-full h-full object-cover" 
                             src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                             alt="Our Team">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    </div>
                </div>
                <div class="space-y-4 fade-in-left">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Our Mission</h3>
                        <p class="text-base text-gray-600 leading-relaxed">
                            At LearnHub, we believe that quality education should be accessible to everyone, regardless of their location or background.
                        </p>
                </div>
                <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Our Vision</h3>
                        <p class="text-base text-gray-600 leading-relaxed">
                            We envision a world where everyone has access to the education they need to succeed.
                        </p>
                </div>
                <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Our Values</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-base text-gray-600">Excellence in Education</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-base text-gray-600">Innovation in Learning</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-base text-gray-600">Student Success</span>
                        </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-base text-gray-600">Global Community</span>
                        </li>
                    </ul>
                </div>
                    <div class="pt-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors duration-300">
                            Join Our Community
                            <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 fade-in-up">
                <h2 class="text-sm font-semibold text-indigo-600 tracking-wide uppercase">Testimonials</h2>
                <p class="mt-2 text-3xl sm:text-4xl font-extrabold text-gray-900">
                    What Our Students Say
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Testimonial 1 -->
                <div class="group bg-white p-8 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 fade-in-up">
                    <div class="flex items-center mb-6">
                        <img class="h-12 w-12 rounded-full ring-2 ring-white shadow-sm" src="https://ui-avatars.com/api/?name=Michael+Zhang&background=random" alt="Michael Zhang">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Michael Zhang</h4>
                            <p class="text-sm text-gray-500">Web Development Student</p>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed">"The courses are well-structured and the instructors are very knowledgeable. I've learned so much in such a short time!"</p>
                    <div class="mt-6 flex text-yellow-400">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="group bg-white p-8 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 fade-in-up fade-in-delay-100">
                    <div class="flex items-center mb-6">
                        <img class="h-12 w-12 rounded-full ring-2 ring-white shadow-sm" src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=random" alt="Sarah Johnson">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Sarah Johnson</h4>
                            <p class="text-sm text-gray-500">Data Science Student</p>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed">"The platform is intuitive and the support team is always ready to help. I've gained valuable skills that helped me land my dream job!"</p>
                    <div class="mt-6 flex text-yellow-400">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="group bg-white p-8 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 fade-in-up fade-in-delay-200">
                    <div class="flex items-center mb-6">
                        <img class="h-12 w-12 rounded-full ring-2 ring-white shadow-sm" src="https://ui-avatars.com/api/?name=David+Patel&background=random" alt="David Patel">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">David Patel</h4>
                            <p class="text-sm text-gray-500">Business Student</p>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed">"The quality of content and the interactive learning experience is outstanding. Highly recommended for anyone looking to upskill!"</p>
                    <div class="mt-6 flex text-yellow-400">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Plan Section -->
    <section class="py-8 sm:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 fade-in-up">
                <h2 class="text-sm font-semibold tracking-wide text-indigo-600 uppercase">PREMIUM ACCESS</h2>
                <p class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Unlock All Features</p>
                <p class="mt-3 text-base text-gray-500">Get unlimited access to all premium courses and exclusive features</p>
            </div>

            <div class="max-w-lg mx-auto bg-white rounded-2xl shadow-sm fade-in-up fade-in-delay-100">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col">
                        <h3 class="text-xl font-semibold text-gray-900">Premium Membership</h3>
                        
                        <ul class="mt-6 space-y-4">
                            <li class="flex items-center text-gray-700">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Access to all premium courses
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Exclusive course materials
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Priority support
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Certificate of completion
                            </li>
                        </ul>

                        <div class="mt-8 bg-gray-50 rounded-xl p-6">
                            <div class="text-center">
                                <p class="text-sm font-medium text-indigo-600">Monthly Subscription</p>
                                <div class="mt-3 flex items-center justify-center">
                                    <span class="text-4xl font-bold text-gray-900">$29.99</span>
                                    <span class="ml-2 text-gray-500">/month</span>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Cancel anytime</p>
                            </div>
                        </div>

                        <a href="{{ route('register') }}" 
                           class="mt-6 block w-full text-center px-6 py-3 text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative overflow-hidden py-20 bg-gradient-to-br from-indigo-700 via-indigo-600 to-purple-700">
        <!-- Enhanced background patterns -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-grid-white/[0.08] bg-[size:40px_40px]"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            <!-- Animated circles -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative container mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Text content with centered alignment -->
                <div class="mb-12 fade-in-right">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-6">
                        <span class="block">Ready to transform your future?</span>
                        <span class="block text-indigo-200 mt-3">Start your learning journey today.</span>
                    </h2>
                    <p class="text-lg sm:text-xl text-indigo-100 max-w-3xl mx-auto leading-relaxed">
                        Join thousands of students already learning with us and take the next step in your career.
                    </p>
                </div>
                
                <!-- CTA buttons with improved block-style layout -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center max-w-lg mx-auto fade-in-left fade-in-delay-100">
                    <a href="{{ route('register') }}" 
                    <a href="{{ route('register') }}" 
                       class="w-full sm:flex-1 group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-xl text-indigo-700 bg-white hover:bg-indigo-50 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        Get started now
                        <svg class="ml-3 h-5 w-5 transform group-hover:translate-x-1.5 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="{{ route('client.courses.index') }}" 
                       class="w-full sm:flex-1 group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-xl text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 transition-all duration-300 transform hover:scale-105">
                        Browse Courses
                        <svg class="ml-3 h-5 w-5 transform group-hover:translate-x-1.5 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Course slider navigation
        const sliderContainer = document.querySelector('.overflow-x-auto');
        const prevButton = document.querySelector('[data-slider-prev]');
        const nextButton = document.querySelector('[data-slider-next]');
        
        if (sliderContainer && prevButton && nextButton) {
            prevButton.addEventListener('click', () => {
                sliderContainer.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                });
            });
            
            nextButton.addEventListener('click', () => {
                sliderContainer.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            });

            // Show/hide arrows based on scroll position
            sliderContainer.addEventListener('scroll', () => {
                const isStart = sliderContainer.scrollLeft === 0;
                const isEnd = sliderContainer.scrollLeft + sliderContainer.clientWidth >= sliderContainer.scrollWidth;
                
                prevButton.style.opacity = isStart ? '0.5' : '1';
                nextButton.style.opacity = isEnd ? '0.5' : '1';
                prevButton.style.cursor = isStart ? 'not-allowed' : 'pointer';
                nextButton.style.cursor = isEnd ? 'not-allowed' : 'pointer';
            });
        }
        
        // Trigger animations on page load
        setTimeout(() => {
            const animatedElements = document.querySelectorAll('.fade-in, .fade-in-up, .fade-in-left, .fade-in-right');
            animatedElements.forEach(el => {
                el.style.animationPlayState = 'running';
            });
        }, 100);
    });
</script>
@endpush
