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
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Dashboard Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Learning Dashboard</h1>
                    <p class="mt-2 text-gray-600">Track your progress and continue learning</p>
                </div>
                <div class="hidden sm:block">
                    <a href="{{ route('client.courses.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Explore More Courses
                    </a>
                </div>
            </div>
            
            <!-- Course Type Filter -->
            <div class="mt-4 flex gap-2">
                <button onclick="filterCourses('all')" 
                        class="course-filter px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 bg-indigo-600 text-white" 
                        data-filter="all">
                    All Courses
                </button>
                <button onclick="filterCourses('free')" 
                        class="course-filter px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 bg-gray-200 text-gray-700 hover:bg-gray-300" 
                        data-filter="free">
                    Free Courses
                </button>
                <button onclick="filterCourses('premium')" 
                        class="course-filter px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 bg-gray-200 text-gray-700 hover:bg-gray-300" 
                        data-filter="premium">
                    Premium Courses
                </button>
                <button onclick="filterCourses('completed')" 
                        class="course-filter px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 bg-gray-200 text-gray-700 hover:bg-gray-300" 
                        data-filter="completed">
                    Completed
                </button>
                <button onclick="filterCourses('in_progress')" 
                        class="course-filter px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 bg-gray-200 text-gray-700 hover:bg-gray-300" 
                        data-filter="in_progress">
                    In Progress
                </button>
            </div>
        </div>

        @if($enrollments->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <img src="https://illustrations.popsy.co/gray/student-taking-online-course.svg" 
                     alt="No courses" 
                     class="w-64 h-64 mx-auto mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Start Your Learning Journey</h2>
                <p class="text-gray-500 mb-6">Explore our courses and begin your learning adventure today.</p>
                <a href="{{ route('client.courses.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Browse Courses
                </a>
            </div>
        @else
            <!-- Learning Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $stats->total }}</h3>
                            <p class="text-sm text-gray-500">Enrolled Courses</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $stats->completed }}</h3>
                            <p class="text-sm text-gray-500">Completed Courses</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $stats->in_progress }}</h3>
                            <p class="text-sm text-gray-500">In Progress</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enrollments as $enrollment)
                    @php
                        $totalLessons = $enrollment->course->lessons->count();
                        $completedLessons = \App\Models\LessonProgress::where('user_id', auth()->id())
                            ->whereIn('lesson_id', $enrollment->course->lessons->pluck('id'))
                            ->where('completed', true)
                            ->count();
                        $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                        $courseStatus = $progressPercentage >= 95 ? 'completed' : 'in_progress';
                    @endphp
                    <div class="course-card bg-white rounded-lg shadow-sm overflow-hidden" 
                         data-course-type="{{ $enrollment->course->is_free ? 'free' : 'premium' }}"
                         data-course-status="{{ $courseStatus }}">
                        <!-- Course Thumbnail -->
                        <div class="relative h-48">
                            <img src="{{ $enrollment->course->thumbnail }}" 
                                 alt="{{ $enrollment->course->title }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=800&q=80'">
                            <!-- Progress Indicator -->
                            <div class="absolute top-4 right-4">
                                <svg class="progress-ring w-12 h-12">
                                    <circle class="text-gray-200" 
                                            stroke-width="3" 
                                            stroke="currentColor" 
                                            fill="transparent" 
                                            r="18" 
                                            cx="24" 
                                            cy="24"/>
                                    <circle class="text-indigo-600 progress-circle" 
                                            stroke-width="3" 
                                            stroke="currentColor" 
                                            fill="transparent" 
                                            r="18" 
                                            cx="24" 
                                            cy="24" 
                                            stroke-dasharray="113.097" 
                                            stroke-dashoffset="113.097"
                                            data-progress="{{ $progressPercentage }}"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center text-xs font-medium text-indigo-600">
                                    {{ $progressPercentage }}%
                                </div>
                            </div>
                        </div>

                        <!-- Course Content -->
                        <div class="p-6">
                            <div class="flex items-center mb-2 gap-2">
                                <span class="px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-600 rounded-full">
                                    {{ $enrollment->course->category->name }}
                                </span>
                                @if($enrollment->course->is_free)
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-600 rounded-full">
                                        Free
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-600 rounded-full">
                                        Premium
                                    </span>
                                @endif
                                <span class="text-xs text-gray-500">
                                    Enrolled {{ $enrollment->enrolled_at->diffForHumans() }}
                                </span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $enrollment->course->title }}
                            </h3>
                            <div class="flex items-center mb-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($enrollment->course->instructor->name) }}&background=random" 
                                     alt="{{ $enrollment->course->instructor->name }}"
                                     class="w-6 h-6 rounded-full">
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ $enrollment->course->instructor->name }}
                                </span>
                            </div>

                            <!-- Course Progress -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Progress</span>
                                    <span class="text-gray-900 font-medium">{{ $completedLessons }}/{{ $totalLessons }} lessons</span>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-indigo-600 rounded-full progress-bar" data-progress="{{ $progressPercentage }}"></div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('client.courses.learn', $enrollment->course->slug) }}" 
                               class="group block w-full text-center px-4 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 font-medium rounded-md transition-all duration-200 ease-in-out">
                                <span class="inline-flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Continue Learning</span>
                                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">
                    @if ($enrollments->previousPageUrl())
                        <a href="{{ $enrollments->previousPageUrl() }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Previous
                        </a>
                    @endif

                    @if ($enrollments->hasMorePages())
                        <a href="{{ $enrollments->nextPageUrl() }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Next
                        </a>
                    @endif
                </nav>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update progress circles
    document.querySelectorAll('.progress-circle').forEach(circle => {
        const progress = circle.dataset.progress;
        circle.style.strokeDashoffset = 113.097 * (1 - (progress / 100));
    });

    // Update progress bars
    document.querySelectorAll('.progress-bar').forEach(bar => {
        const progress = bar.dataset.progress;
        bar.style.width = progress + '%';
    });

    // Course filtering functionality
    window.filterCourses = function(type) {
        // Update filter buttons
        document.querySelectorAll('.course-filter').forEach(button => {
            if (button.dataset.filter === type) {
                button.classList.remove('bg-gray-200', 'text-gray-700');
                button.classList.add('bg-indigo-600', 'text-white');
            } else {
                button.classList.remove('bg-indigo-600', 'text-white');
                button.classList.add('bg-gray-200', 'text-gray-700');
            }
        });

        // Filter courses
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
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
});
</script>
@endpush