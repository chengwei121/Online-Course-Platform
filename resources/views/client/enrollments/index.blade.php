@extends('layouts.client')

@section('title', 'My Learning Dashboard')

@push('styles')
<style>
    .progress-ring {
        transform: rotate(-90deg);
    }
    
    .course-card {
        transition: all 0.3s ease;
        background-color: rgba(255, 255, 255, 1) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
    }

    .course-card h3 {
        color: rgba(17, 24, 39, 1);
    }

    .course-card .text-gray-600 {
        color: rgba(75, 85, 99, 1);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: rgba(17, 24, 39, 1);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(79, 70, 229, 1);
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen pt-20 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Dashboard Header -->
        <div class="mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">My Learning Dashboard</h1>
                    <p class="text-lg text-gray-600">Track your progress and continue learning</p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('client.courses.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition duration-200 shadow-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Explore More Courses
                    </a>
                </div>
            </div>
            
            <!-- Course Type Filter -->
            <div class="flex flex-wrap gap-3">
                <button onclick="filterCourses('all')" 
                        class="course-filter px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 bg-indigo-600 text-white shadow-sm" 
                        data-filter="all">
                    All Courses
                </button>
                <button onclick="filterCourses('free')" 
                        class="course-filter px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 shadow-sm" 
                        data-filter="free">
                    Free Courses
                </button>
                <button onclick="filterCourses('premium')" 
                        class="course-filter px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 shadow-sm" 
                        data-filter="premium">
                    Premium Courses
                </button>
                <button onclick="filterCourses('completed')" 
                        class="course-filter px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 shadow-sm" 
                        data-filter="completed">
                    Completed
                </button>
                <button onclick="filterCourses('in_progress')" 
                        class="course-filter px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 shadow-sm" 
                        data-filter="in_progress">
                    In Progress
                </button>
            </div>
        </div>

        @if($enrollments->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="max-w-md mx-auto">
                    <img src="https://illustrations.popsy.co/gray/student-taking-online-course.svg" 
                         alt="No courses" 
                         class="w-48 h-48 mx-auto mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">Start Your Learning Journey</h2>
                    <p class="text-gray-600 mb-8 leading-relaxed">Explore our courses and begin your learning adventure today.</p>
                    <a href="{{ route('client.courses.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Browse Courses
                    </a>
                </div>
            </div>
        @else
            <!-- Learning Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-indigo-100">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats->total }}</h3>
                            <p class="text-sm text-gray-600 font-medium">Enrolled Courses</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats->completed }}</h3>
                            <p class="text-sm text-gray-600 font-medium">Completed Courses</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-yellow-100">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats->in_progress }}</h3>
                            <p class="text-sm text-gray-600 font-medium">In Progress</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8" id="courseGrid">
                @foreach($enrollments as $enrollment)
                    @php
                        $totalLessons = $enrollment->course->lessons->count();
                        $completedLessons = \App\Models\LessonProgress::where('user_id', auth()->id())
                            ->whereIn('lesson_id', $enrollment->course->lessons->pluck('id'))
                            ->where('completed', true)
                            ->count();
                        $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                        $courseStatus = $progressPercentage == 100 ? 'completed' : ($progressPercentage > 0 ? 'in_progress' : 'not_started');
                    @endphp
                    <div class="course-card bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100" 
                         data-course-type="{{ $enrollment->course->is_free ? 'free' : 'premium' }}"
                         data-course-status="{{ $courseStatus }}">
                        <!-- Course Thumbnail -->
                        <div class="relative h-40">
                            <img src="{{ $enrollment->course->thumbnail }}" 
                                 alt="{{ $enrollment->course->title }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=800&q=80'">
                            <!-- Progress Indicator -->
                            <div class="absolute top-3 right-3">
                                <div class="relative">
                                    <svg class="progress-ring w-14 h-14">
                                        <circle class="text-white/20" 
                                                stroke-width="3" 
                                                stroke="currentColor" 
                                                fill="transparent" 
                                                r="22" 
                                                cx="28" 
                                                cy="28"/>
                                        <circle class="text-white progress-circle" 
                                                stroke-width="3" 
                                                stroke="currentColor" 
                                                fill="transparent" 
                                                r="22" 
                                                cx="28" 
                                                cy="28" 
                                                stroke-dasharray="138.23" 
                                                stroke-dashoffset="138.23"
                                                data-progress="{{ $progressPercentage }}"/>
                                    </svg>
                                    <!-- Dark background circle for better visibility -->
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-9 h-9 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center">
                                            <span class="text-xs font-bold text-white drop-shadow-md">{{ $progressPercentage }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Course Content -->
                        <div class="p-4">
                            <div class="flex items-center flex-wrap gap-2 mb-3">
                                <span class="px-2.5 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-700 rounded-full">
                                    {{ $enrollment->course->category->name }}
                                </span>
                                @if($enrollment->course->is_free)
                                    <span class="px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                                        Free
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">
                                        Premium
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-base font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $enrollment->course->title }}
                            </h3>
                            
                            <div class="flex items-center mb-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($enrollment->course->instructor->name) }}&background=random" 
                                     alt="{{ $enrollment->course->instructor->name }}"
                                     class="w-7 h-7 rounded-full">
                                <span class="ml-2 text-xs text-gray-600 font-medium">
                                    {{ $enrollment->course->instructor->name }}
                                </span>
                            </div>

                            <div class="text-xs text-gray-500 mb-3">
                                Enrolled {{ $enrollment->enrolled_at->diffForHumans() }}
                            </div>

                            <!-- Course Progress -->
                            <div class="mb-4">
                                <div class="flex justify-between text-xs mb-1.5">
                                    <span class="text-gray-600 font-medium">Progress</span>
                                    <span class="text-gray-900 font-semibold">{{ $completedLessons }}/{{ $totalLessons }} lessons</span>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 {{ $courseStatus === 'completed' ? 'bg-green-500' : 'bg-indigo-500' }} rounded-full progress-bar transition-all duration-500" data-progress="{{ $progressPercentage }}"></div>
                                </div>
                            </div>

                            <!-- Completion Status Badge -->
                            @if($courseStatus === 'completed')
                                <div class="mb-3">
                                    <div class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Course Completed!
                                    </div>
                                </div>
                            @elseif($courseStatus === 'in_progress')
                                <div class="mb-3">
                                    <div class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        In Progress
                                    </div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <div class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Not Started
                                    </div>
                                </div>
                            @endif

                            <!-- Action Button -->
                            <a href="{{ route('client.courses.learn', $enrollment->course->slug) }}" 
                               class="group block w-full text-center px-3 py-2.5 {{ $courseStatus === 'completed' ? 'bg-green-600 hover:bg-green-700' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white text-sm font-medium rounded-lg transition-all duration-200 ease-in-out shadow-sm">
                                <span class="inline-flex items-center justify-center">
                                    @if($courseStatus === 'completed')
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Review Course</span>
                                    @elseif($courseStatus === 'in_progress')
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Continue Learning</span>
                                    @else
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Start Learning</span>
                                    @endif
                                    <svg class="w-3.5 h-3.5 ml-1.5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- No Courses Found Message (for filters) -->
            <div id="noCourseMessage" class="bg-white rounded-xl shadow-sm p-12 text-center" style="display: none;">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.466-.943-6.009-2.47M15 21v-9a6 6 0 00-12 0v9a1 1 0 001 1h10a1 1 0 001-1z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">No Courses Found</h3>
                    <p class="text-gray-600 mb-6">No courses match the selected filter. Try selecting a different filter or explore new courses.</p>
                    <button onclick="filterCourses('all')" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition duration-200 shadow-sm mr-3">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Show All Courses
                    </button>
                    <a href="{{ route('client.courses.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg hover:bg-gray-50 border border-gray-300 transition duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Browse New Courses
                    </a>
                </div>
            </div>

            <!-- Pagination -->
            @if($enrollments->hasPages())
                <div class="mt-12">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <!-- Page Info -->
                        <div class="text-sm text-gray-700">
                            @if(method_exists($enrollments, 'total'))
                                <span class="font-medium">Showing {{ $enrollments->firstItem() ?? 0 }} to {{ $enrollments->lastItem() ?? 0 }} of {{ $enrollments->total() }} courses</span>
                            @else
                                <span class="font-medium">Showing {{ $enrollments->count() }} courses</span>
                            @endif
                        </div>

                        <!-- Pagination Navigation -->
                        <nav class="flex items-center space-x-1">
                            <!-- Previous Button -->
                            @if ($enrollments->onFirstPage())
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Previous
                                </span>
                            @else
                                <a href="{{ $enrollments->previousPageUrl() }}" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-indigo-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Previous
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            @php
                                $currentPage = $enrollments->currentPage();
                                $lastPage = $enrollments->lastPage();
                                $showPages = 5; // Number of page links to show
                                $halfShow = floor($showPages / 2);
                                
                                $startPage = max(1, $currentPage - $halfShow);
                                $endPage = min($lastPage, $currentPage + $halfShow);
                                
                                // Adjust if we're near the beginning or end
                                if ($endPage - $startPage + 1 < $showPages) {
                                    if ($startPage == 1) {
                                        $endPage = min($lastPage, $startPage + $showPages - 1);
                                    } else {
                                        $startPage = max(1, $endPage - $showPages + 1);
                                    }
                                }
                            @endphp

                            <!-- First Page -->
                            @if($startPage > 1)
                                <a href="{{ $enrollments->url(1) }}" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-indigo-600 transition-colors duration-200">
                                    1
                                </a>
                                @if($startPage > 2)
                                    <span class="px-2 py-2 text-sm text-gray-500">...</span>
                                @endif
                            @endif

                            <!-- Page Numbers -->
                            @for($page = $startPage; $page <= $endPage; $page++)
                                @if($page == $currentPage)
                                    <span class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 border border-indigo-600 rounded-lg shadow-sm">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $enrollments->url($page) }}" 
                                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-indigo-600 transition-colors duration-200">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            <!-- Last Page -->
                            @if($endPage < $lastPage)
                                @if($endPage < $lastPage - 1)
                                    <span class="px-2 py-2 text-sm text-gray-500">...</span>
                                @endif
                                <a href="{{ $enrollments->url($lastPage) }}" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-indigo-600 transition-colors duration-200">
                                    {{ $lastPage }}
                                </a>
                            @endif

                            <!-- Next Button -->
                            @if ($enrollments->hasMorePages())
                                <a href="{{ $enrollments->nextPageUrl() }}" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-indigo-600 transition-colors duration-200">
                                    Next
                                    <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                                    Next
                                    <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>

                    <!-- Mobile Pagination Summary -->
                    <div class="mt-4 text-center sm:hidden">
                        <span class="text-sm text-gray-600">
                            Page {{ $enrollments->currentPage() }} of {{ $enrollments->lastPage() }}
                        </span>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update progress circles with animation (new radius = 22, circumference = 2 * PI * r = 138.23)
    document.querySelectorAll('.progress-circle').forEach(circle => {
        const progress = circle.dataset.progress;
        setTimeout(() => {
            circle.style.strokeDashoffset = 138.23 * (1 - (progress / 100));
        }, 500);
    });

    // Update progress bars with animation
    document.querySelectorAll('.progress-bar').forEach(bar => {
        const progress = bar.dataset.progress;
        setTimeout(() => {
            bar.style.width = progress + '%';
        }, 700);
    });

    // Course filtering functionality
    window.filterCourses = function(type) {
        // Update filter buttons
        document.querySelectorAll('.course-filter').forEach(button => {
            if (button.dataset.filter === type) {
                button.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-200');
                button.classList.add('bg-indigo-600', 'text-white');
            } else {
                button.classList.remove('bg-indigo-600', 'text-white');
                button.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-200');
            }
        });

        // Filter courses with animation
        let visibleCourses = 0;
        document.querySelectorAll('.course-card').forEach(card => {
            const courseType = card.dataset.courseType;
            const courseStatus = card.dataset.courseStatus;
            let shouldShow = false;
            
            if (type === 'all') {
                shouldShow = true;
            } else if (type === 'free' || type === 'premium') {
                shouldShow = courseType === type;
            } else if (type === 'completed' || type === 'in_progress') {
                shouldShow = courseStatus === type;
            }
            
            if (shouldShow) {
                visibleCourses++;
                card.style.display = 'block';
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                    card.style.transition = 'all 0.3s ease';
                }, 100);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });

        // Show/hide "no courses found" message
        const noCoursesMessage = document.getElementById('noCourseMessage');
        const courseGrid = document.getElementById('courseGrid');
        
        if (visibleCourses === 0) {
            courseGrid.style.display = 'none';
            noCoursesMessage.style.display = 'block';
            noCoursesMessage.style.opacity = '0';
            setTimeout(() => {
                noCoursesMessage.style.opacity = '1';
                noCoursesMessage.style.transition = 'opacity 0.3s ease';
            }, 100);
        } else {
            noCoursesMessage.style.display = 'none';
            courseGrid.style.display = 'grid';
        }
    }
});
</script>
@endpush