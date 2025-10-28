@extends('teacher.layouts.app')

@section('title', 'Grade Submission')

@section('content')
<!-- Toast Notification -->
@if(session('success'))
<div id="toast-success" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-md p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-lg border-l-4 border-green-500 animate-slide-in">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
        </svg>
    </div>
    <div class="ml-3 text-sm font-medium text-gray-900">{{ session('success') }}</div>
    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" onclick="document.getElementById('toast-success').remove()">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
</div>
@endif

@if(session('error'))
<div id="toast-error" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-md p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-lg border-l-4 border-red-500 animate-slide-in">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </div>
    <div class="ml-3 text-sm font-medium text-gray-900">{{ session('error') }}</div>
    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" onclick="document.getElementById('toast-error').remove()">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
</div>
@endif

<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="mb-4">
        <a href="{{ route('teacher.assignments.submissions', $submission->assignment) }}" 
           class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 mb-3">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Submissions
        </a>

        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Grade Submission</h1>
                <p class="text-gray-600 mt-1">{{ $submission->assignment->title }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Left Column: Submission Details -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Student Info Card -->
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-indigo-600 font-bold text-lg">
                            {{ substr($submission->student->user->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-gray-900">{{ $submission->student->user->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $submission->student->user->email }}</p>
                        
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $submission->status === 'submitted' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $submission->status === 'graded' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ ucfirst($submission->status) }}
                            </span>
                            
                            @if($submission->submitted_at > $submission->assignment->due_date)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Late Submission
                            </span>
                            @endif
                        </div>
                        
                        <div class="mt-3 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Submitted:</span>
                                <span class="font-medium text-gray-900 ml-2">
                                    {{ $submission->submitted_at->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500">Due Date:</span>
                                <span class="font-medium text-gray-900 ml-2">
                                    @if($submission->assignment->due_date)
                                        {{ $submission->assignment->due_date->format('M d, Y') }}
                                    @else
                                        No deadline
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Instructions -->
            <div class="bg-white rounded-lg shadow-sm p-4">
                <h3 class="text-base font-semibold text-gray-900 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Assignment Instructions
                </h3>
                <div class="prose max-w-none text-gray-700 text-sm leading-relaxed bg-gray-50 p-4 rounded-lg">
                    {!! nl2br(e($submission->assignment->description)) !!}
                </div>
            </div>

            <!-- Student Submission -->
            <div class="bg-white rounded-lg shadow-sm p-4">
                <h3 class="text-base font-semibold text-gray-900 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Student's Work
                </h3>
                
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <p class="text-gray-800 text-sm leading-relaxed whitespace-pre-wrap">{{ $submission->submission_text }}</p>
                </div>

                @if($submission->submission_file)
                <div class="mt-4 flex items-center p-4 bg-gray-50 rounded-lg">
                    <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Attachment</p>
                        <p class="text-xs text-gray-500">{{ basename($submission->submission_file) }}</p>
                    </div>
                    <a href="{{ route('teacher.submissions.download', $submission->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Grading Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Grading
                </h3>

                <form action="{{ route('teacher.submissions.update', $submission) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Score Input -->
                    <div class="mb-4">
                        <label for="score" class="block text-sm font-semibold text-gray-700 mb-2">
                            Score <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="score" 
                                   name="score" 
                                   min="0" 
                                   max="100" 
                                   step="1"
                                   value="{{ old('score', $submission->score) }}"
                                   required
                                   oninput="validateScore(this)"
                                   class="w-full px-4 py-2.5 text-lg font-semibold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="0-100">
                            <span class="absolute right-4 top-2.5 text-gray-500 font-medium">/100</span>
                        </div>
                        @error('score')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quick Grade Buttons -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quick Grades</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" onclick="setScore(100)" 
                                    class="px-3 py-2 text-sm font-medium rounded-lg bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                                A+ (100)
                            </button>
                            <button type="button" onclick="setScore(95)" 
                                    class="px-3 py-2 text-sm font-medium rounded-lg bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                                A (95)
                            </button>
                            <button type="button" onclick="setScore(90)" 
                                    class="px-3 py-2 text-sm font-medium rounded-lg bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                A- (90)
                            </button>
                            <button type="button" onclick="setScore(85)" 
                                    class="px-3 py-2 text-sm font-medium rounded-lg bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                B+ (85)
                            </button>
                            <button type="button" onclick="setScore(80)" 
                                    class="px-3 py-2 text-sm font-medium rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition-colors">
                                B (80)
                            </button>
                            <button type="button" onclick="setScore(75)" 
                                    class="px-3 py-2 text-sm font-medium rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition-colors">
                                B- (75)
                            </button>
                            <button type="button" onclick="setScore(70)" 
                                    class="px-3 py-2 text-sm font-medium rounded-lg bg-orange-100 text-orange-800 hover:bg-orange-200 transition-colors">
                                C+ (70)
                            </button>
                            <button type="button" onclick="setScore(65)" 
                                    class="px-3 py-2 text-sm font-medium rounded-lg bg-orange-100 text-orange-800 hover:bg-orange-200 transition-colors">
                                C (65)
                            </button>
                            <button type="button" onclick="setScore(60)" 
                                    class="px-3 py-2 text-sm font-medium rounded-lg bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
                                D (60)
                            </button>
                        </div>
                    </div>

                    <!-- Feedback Textarea -->
                    <div class="mb-4">
                        <label for="feedback" class="block text-sm font-semibold text-gray-700 mb-2">
                            Feedback <span class="text-red-500">*</span>
                        </label>
                        <textarea id="feedback" 
                                  name="feedback" 
                                  rows="5" 
                                  required
                                  class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Provide constructive feedback to the student...">{{ old('feedback', $submission->feedback) }}</textarea>
                        @error('feedback')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Private Notes (Optional) -->
                    <div class="mb-4">
                        <label for="private_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Private Notes (Not visible to student)
                        </label>
                        <textarea id="private_notes" 
                                  name="private_notes" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                  placeholder="Notes for your records...">{{ old('private_notes', $submission->private_notes ?? '') }}</textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="space-y-2">
                        <button type="submit" 
                                class="w-full px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $submission->score !== null ? 'Update Grade' : 'Submit Grade' }}
                        </button>

                        <a href="{{ route('teacher.assignments.submissions', $submission->assignment) }}" 
                           class="w-full block text-center px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            Cancel
                        </a>
                    </div>
                </form>

                <!-- Grading History -->
                @if($submission->score !== null && $submission->graded_at)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Grading History</h4>
                    <div class="text-xs text-gray-500 space-y-1">
                        <p>Graded: {{ $submission->graded_at->format('M d, Y h:i A') }}</p>
                        @if($submission->graded_by)
                        <p>By: {{ $submission->gradedBy->name ?? 'Unknown' }}</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function validateScore(input) {
        let value = parseInt(input.value);
        
        // Remove any non-numeric characters except numbers
        input.value = input.value.replace(/[^0-9]/g, '');
        
        // Parse the value
        value = parseInt(input.value);
        
        // If value is less than 0, set to 0
        if (value < 0 || isNaN(value)) {
            input.value = 0;
        }
        
        // If value is greater than 100, set to 100
        if (value > 100) {
            input.value = 100;
        }
    }

    function setScore(score) {
        document.getElementById('score').value = score;
    }

    // Auto-hide toast after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toastSuccess = document.getElementById('toast-success');
        const toastError = document.getElementById('toast-error');
        
        if (toastSuccess) {
            setTimeout(() => {
                toastSuccess.style.transition = 'opacity 0.5s ease-out';
                toastSuccess.style.opacity = '0';
                setTimeout(() => toastSuccess.remove(), 500);
            }, 5000);
        }
        
        if (toastError) {
            setTimeout(() => {
                toastError.style.transition = 'opacity 0.5s ease-out';
                toastError.style.opacity = '0';
                setTimeout(() => toastError.remove(), 500);
            }, 5000);
        }
    });
</script>

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
