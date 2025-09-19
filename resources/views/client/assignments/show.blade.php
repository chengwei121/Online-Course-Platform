@extends('layouts.client')

@section('title', $assignment->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $assignment->title }}</h1>
            
            <!-- Assignment Details -->
            <div class="prose max-w-none mb-8">
                <p class="text-gray-600">{{ $assignment->description }}</p>
            </div>

            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-8">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Due: {{ $assignment->due_date->format('M d, Y') }}
                </div>
                @if($submission)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Submitted: {{ $submission->submitted_at->format('M d, Y h:i A') }}
                    </div>
                @endif
            </div>

            <!-- Submission Form -->
            @if(!$submission || is_null($submission->score))
                <div class="border-t border-gray-200 pt-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Your Submission</h2>
                    <form action="{{ route('client.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700">
                                    Assignment Content
                                </label>
                                <div class="mt-1">
                                    <textarea id="content" 
                                              name="content" 
                                              rows="8" 
                                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                              placeholder="Write your assignment submission here...">{{ $submission ? $submission->submission_text : '' }}</textarea>
                                </div>
                                @error('content')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700">
                                    Attachment (optional)
                                </label>
                                <div class="mt-1">
                                    <input type="file" 
                                           id="file" 
                                           name="file" 
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @if($submission && $submission->submission_file)
                                    <p class="mt-2 text-sm text-gray-500">
                                        Current file: 
                                        <a href="{{ asset('storage/' . $submission->submission_file) }}" 
                                           class="text-indigo-600 hover:text-indigo-500"
                                           target="_blank">
                                            View Attachment
                                        </a>
                                    </p>
                                @endif
                                @error('file')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ $submission ? 'Update Submission' : 'Submit Assignment' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <!-- Graded Submission Display -->
                <div class="border-t border-gray-200 pt-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Your Graded Submission</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700">Your Work</h3>
                            <p class="mt-2 text-gray-600">{{ $submission->submission_text }}</p>
                            @if($submission->submission_file)
                                <a href="{{ asset('storage/' . $submission->submission_file) }}" 
                                   class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-500"
                                   target="_blank">
                                    View Attachment
                                </a>
                            @endif
                        </div>
                        
                        @if($submission->feedback)
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h3 class="text-sm font-medium text-gray-700">Instructor Feedback</h3>
                                <p class="mt-2 text-gray-600">{{ $submission->feedback }}</p>
                            </div>
                        @endif
                        
                        @if($submission->score)
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h3 class="text-sm font-medium text-gray-700">Grade</h3>
                                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $submission->score }}/100</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 