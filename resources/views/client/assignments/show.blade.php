@extends('layouts.client')

@section('title', $assignment->title)

@section('content')
@if(session('success'))
<div class="fixed top-24 right-6 z-50 max-w-md">
    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-lg animate-slide-in">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-green-400 hover:text-green-600">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        const alert = document.querySelector('.animate-slide-in');
        if (alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.parentElement.remove(), 500);
        }
    }, 5000);
</script>
@endif

<div class="max-w-4xl mx-auto py-8 px-4 mt-20">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-5">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('client.courses.learn', ['course' => $assignment->lesson->course->slug]) }}" 
                   class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Course
                </a>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $assignment->title }}</h1>
            
            <!-- Assignment Metadata -->
            <div class="flex flex-wrap items-center gap-2 text-sm mb-4">
                <div class="flex items-center text-gray-600">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Due:</span> 
                    @if($assignment->due_date)
                        {{ $assignment->due_date->format('M d, Y') }}
                    @else
                        No deadline
                    @endif
                </div>
                
                <div class="flex items-center text-gray-600">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Type:</span> {{ ucfirst($assignment->type) }}
                </div>

                @if($assignment->points)
                <div class="flex items-center text-gray-600">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    <span class="font-medium">Points:</span> {{ $assignment->points }}
                </div>
                @endif

                @if($assignment->difficulty)
                <div class="flex items-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $assignment->difficulty === 'easy' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $assignment->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $assignment->difficulty === 'hard' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($assignment->difficulty) }}
                    </span>
                </div>
                @endif

                @if($submission)
                    <div class="flex items-center">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $submission->status === 'submitted' && is_null($submission->score) ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $submission->status === 'submitted' && !is_null($submission->score) ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $submission->status === 'graded' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $submission->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}">
                            @if($submission->status === 'submitted' && is_null($submission->score))
                                Pending Review
                            @else
                                {{ ucfirst($submission->status) }}
                            @endif
                        </span>
                    </div>
                @endif
            </div>

            <!-- Assignment Instructions -->
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 mb-4">
                <h2 class="text-base font-semibold text-blue-900 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Assignment Instructions
                </h2>
                <div class="prose max-w-none text-gray-700 text-sm leading-relaxed">
                    @if($assignment->description)
                        {!! nl2br(e($assignment->description)) !!}
                    @else
                        <p class="text-gray-500 italic">No detailed instructions provided. Please check with your instructor.</p>
                    @endif
                </div>
            </div>

            <!-- Submission Form -->
            @if(!$submission || is_null($submission->score))
                <div class="border-t border-gray-200 pt-4">
                    @if($submission && $submission->status === 'submitted')
                        <!-- Pending Status Alert -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-yellow-800">
                                        Your assignment has been submitted and is waiting for teacher review.
                                    </p>
                                    <p class="mt-1 text-sm text-yellow-700">
                                        You can still edit your submission until the teacher grades it.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <h2 class="text-base font-semibold text-gray-900 mb-3">
                        @if($submission && $submission->status === 'submitted')
                            Edit Your Submission
                        @else
                            Your Submission
                        @endif
                    </h2>
                    <form action="{{ route('client.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Assignment Content
                                </label>
                                <div>
                                    <textarea id="content" 
                                              name="content" 
                                              rows="4" 
                                              class="shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full text-sm border-gray-300 rounded-lg p-3"
                                              placeholder="Write your assignment submission here...">{{ $submission ? $submission->submission_text : '' }}</textarea>
                                </div>
                                @error('content')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Attachment (optional)
                                </label>
                                <div>
                                    <input type="file" 
                                           id="file" 
                                           name="file" 
                                           class="shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full text-sm border-gray-300 rounded-lg p-2">
                                </div>
                                @if($submission && $submission->submission_file)
                                    <p class="mt-2 text-sm text-gray-500">
                                        Current file: 
                                        <a href="{{ route('client.assignments.download', $submission->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-700 font-medium underline">
                                            Download Attachment
                                        </a>
                                    </p>
                                @endif
                                @error('file')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-semibold rounded-lg shadow-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                    @if($submission && $submission->status === 'submitted')
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Update Submission
                                    @else
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Submit Assignment
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <!-- Graded Submission Display -->
                <div class="border-t border-gray-200 pt-4">
                    <h2 class="text-base font-semibold text-gray-900 mb-3">Your Graded Submission</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Your Work</h3>
                            <p class="mt-2 text-gray-600 text-sm">{{ $submission->submission_text }}</p>
                            @if($submission->submission_file)
                                <a href="{{ route('client.assignments.download', $submission->id) }}" 
                                   class="mt-2 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700 underline">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Attachment
                                </a>
                            @endif
                        </div>
                        
                        @if($submission->feedback)
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Instructor Feedback</h3>
                                <p class="mt-2 text-gray-600 text-sm">{{ $submission->feedback }}</p>
                            </div>
                        @endif
                        
                        @if($submission->score)
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Grade</h3>
                                <p class="mt-2 text-2xl font-bold text-indigo-600">{{ $submission->score }}/100</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
</style>
@endsection 