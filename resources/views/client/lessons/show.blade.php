@extends('layouts.client')

@section('title', $lesson->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Video Player -->
        <div class="aspect-w-16 aspect-h-9">
            @if($lesson->video_url)
                <video id="lessonVideo" class="w-full h-full object-cover" controls>
                    <source src="{{ Storage::url($lesson->video_url) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @else
                <div class="flex items-center justify-center h-full bg-gray-100">
                    <p class="text-gray-500">No video available</p>
                </div>
            @endif
        </div>

        <!-- Lesson Content -->
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $lesson->title }}</h1>
            
            <div class="prose max-w-none">
                {!! $lesson->description !!}
            </div>

            <!-- Video Progress Debug Info (Remove this in production) -->
            <div id="videoProgress" class="mt-4 p-4 bg-gray-100 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Video Progress (Debug Info)</h3>
                <div class="space-y-1 text-xs text-gray-600">
                    <div>Current Time: <span id="currentTime">0</span>s</div>
                    <div>Max Watched Time: <span id="maxWatched">0</span>s</div>
                    <div>Video Duration: <span id="videoDuration">0</span>s</div>
                    <div>Watch Progress: <span id="watchProgress">0</span>%</div>
                    <div>Completion Status: <span id="completionStatus">Not Started</span></div>
                </div>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <button id="manualComplete" onclick="manualComplete()" 
                            class="mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                        Mark as Complete (Test)
                    </button>
                </div>
            </div>

            <!-- Course Navigation -->
            <div class="mt-8 flex justify-between">
                @if($lesson->previous())
                    <a href="{{ route('client.lessons.show', $lesson->previous()) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Previous Lesson
                    </a>
                @endif

                @if($lesson->next())
                    <a href="{{ route('client.lessons.show', $lesson->next()) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Next Lesson
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('lessonVideo');
    
    if (!video) return;
    
    let maxWatchedTime = 0;
    
    // Make maxWatchedTime globally accessible
    window.maxWatchedTime = 0;
    
    // Track the maximum time the student has watched
    video.addEventListener('timeupdate', function() {
        const currentTime = video.currentTime;
        const duration = video.duration || 0;
        
        // Update max watched time if student is watching continuously
        if (currentTime > maxWatchedTime) {
            maxWatchedTime = currentTime;
            window.maxWatchedTime = maxWatchedTime; // Update global variable
        }
        
        // Update debug info
        updateDebugInfo(currentTime, duration);
    });
    
    // Function to update debug information
    function updateDebugInfo(currentTime, duration) {
        const progressPercentage = duration > 0 ? (maxWatchedTime / duration) * 100 : 0;
        
        document.getElementById('currentTime').textContent = Math.floor(currentTime);
        document.getElementById('maxWatched').textContent = Math.floor(maxWatchedTime);
        document.getElementById('videoDuration').textContent = Math.floor(duration);
        document.getElementById('watchProgress').textContent = Math.floor(progressPercentage);
        document.getElementById('progressBar').style.width = progressPercentage + '%';
        
        // Update completion status
        const statusElement = document.getElementById('completionStatus');
        if (window.lessonProgressUpdated) {
            statusElement.textContent = 'Completed ✓';
            statusElement.className = 'text-green-600 font-semibold';
        } else if (progressPercentage >= 90) {
            statusElement.textContent = 'Ready for Completion (90%+ watched)';
            statusElement.className = 'text-blue-600 font-semibold';
        } else if (progressPercentage >= 50) {
            statusElement.textContent = 'In Progress (50%+ watched)';
            statusElement.className = 'text-yellow-600 font-semibold';
        } else {
            statusElement.textContent = 'Just Started';
            statusElement.className = 'text-gray-600';
        }
    }
    
    // Students can now freely skip through the video without restrictions
    
    // Track progress for lesson completion
    video.addEventListener('timeupdate', function() {
        const currentTime = video.currentTime;
        const duration = video.duration;
        
        if (duration > 0) {
            // Use maxWatchedTime instead of currentTime to ensure they actually watched the content
            const progressPercentage = (maxWatchedTime / duration) * 100;
            
            // Update progress if student has continuously watched at least 90% of the video
            if (progressPercentage >= 90) {
                updateLessonProgress();
            }
        }
    });
    
    // Also check completion when video ends
    video.addEventListener('ended', function() {
        const duration = video.duration;
        if (duration > 0) {
            const progressPercentage = (maxWatchedTime / duration) * 100;
            // If they watched at least 85% and reached the end, mark as complete
            if (progressPercentage >= 85) {
                updateLessonProgress();
            }
        }
    });
    
    // Function to update lesson progress
    function updateLessonProgress() {
        // Only update once per session
        if (window.lessonProgressUpdated) return;
        
        const duration = video.duration || 0;
        const actualProgress = duration > 0 ? (maxWatchedTime / duration) * 100 : 0;
        
        console.log('Attempting to update progress:', {
            maxWatchedTime: maxWatchedTime,
            duration: duration,
            progressPercentage: actualProgress
        });
        
        fetch(`/api/lessons/{{ $lesson->id }}/progress`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                completed: true,
                watched_percentage: Math.min(100, Math.floor(actualProgress))
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.lessonProgressUpdated = true;
                console.log('Lesson progress updated successfully');
                
                // Show success message
                showProgressMessage('Lesson completed successfully! ✓', 'success');
                
                // Update debug info
                updateDebugInfo(video.currentTime, video.duration);
            } else {
                console.error('Failed to update progress:', data);
                showProgressMessage('Failed to update progress. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error updating lesson progress:', error);
            showProgressMessage('Network error. Please check your connection.', 'error');
        });
    }
    
    // Function to show progress messages
    function showProgressMessage(message, type = 'success') {
        // Remove existing messages
        const existingMessage = document.querySelector('.progress-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create message
        const messageDiv = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        messageDiv.className = `progress-message fixed top-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg z-50`;
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z'}"/>
                </svg>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(messageDiv);
        
        // Auto remove message after 4 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 4000);
    }
});

// Manual completion function for testing
function manualComplete() {
    if (window.lessonProgressUpdated) {
        alert('Lesson already completed!');
        return;
    }
    
    const video = document.getElementById('lessonVideo');
    if (!video) {
        alert('No video found!');
        return;
    }
    
    const duration = video.duration || 0;
    const maxWatched = window.maxWatchedTime || 0;
    const progressPercentage = duration > 0 ? (maxWatched / duration) * 100 : 0;
    
    console.log('Manual completion triggered:', {
        duration: duration,
        maxWatched: maxWatched,
        progressPercentage: progressPercentage
    });
    
    if (progressPercentage < 85) {
        alert(`You need to watch at least 85% of the video. Currently watched: ${Math.floor(progressPercentage)}%`);
        return;
    }
    
    // Force completion
    fetch(`/api/lessons/{{ $lesson->id }}/progress`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            completed: true,
            watched_percentage: Math.min(100, Math.floor(progressPercentage))
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.lessonProgressUpdated = true;
            alert('Lesson marked as complete!');
            
            // Update debug info
            const statusElement = document.getElementById('completionStatus');
            statusElement.textContent = 'Completed ✓';
            statusElement.className = 'text-green-600 font-semibold';
        } else {
            alert('Failed to complete lesson: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error occurred');
    });
}
</script>
@endpush
@endsection 