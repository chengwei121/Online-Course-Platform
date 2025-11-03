@extends('layouts.client')

@section('title', 'My Reviews')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Reviews</h1>
                    <p class="text-gray-600 mt-2">All the course reviews you've written</p>
                </div>
                <div class="hidden sm:block">
                    <nav class="flex space-x-1 bg-white rounded-lg p-1 shadow-sm">
                        <a href="{{ route('client.enrollments.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 rounded-md transition-colors">
                            My Learning
                        </a>
                        <a href="{{ route('client.payments.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 rounded-md transition-colors">
                            My Payments
                        </a>
                        <span class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md">
                            My Reviews
                        </span>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Your Reviews</h2>
                <p class="text-sm text-gray-600 mt-1">All the course reviews you've written</p>
            </div>

            @if($reviews->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($reviews as $review)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start space-x-4">
                                <!-- Course Thumbnail -->
                                <div class="flex-shrink-0">
                                    @if($review->course->thumbnail)
                                        <img src="{{ $review->course->thumbnail }}" 
                                             alt="{{ $review->course->title }}"
                                             class="w-16 h-16 rounded-lg object-cover">
                                    @else
                                        <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Review Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $review->course->title }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                Instructor: {{ $review->course->instructor->name ?? 'N/A' }} • 
                                                {{ $review->course->category->name ?? 'General' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="flex items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $review->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-500">
                                                {{ $review->created_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($review->comment)
                                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('client.courses.show', $review->course->slug) }}" 
                                           class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Course
                                        </a>
                                        <a href="{{ route('client.courses.learn', $review->course->slug) }}" 
                                           class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-900">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m-6-8h8a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2z"></path>
                                            </svg>
                                            Continue Learning
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Share your thoughts about the courses you've completed.</p>
                    <div class="mt-6">
                        <a href="{{ route('client.enrollments.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            View My Courses
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection