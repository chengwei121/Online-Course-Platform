@extends('teacher.layouts.app')

@section('title', $lesson->title . ' - ' . $course->title)
@section('page-title', 'Lesson Details')

@section('content')
<div class="container-fluid">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Lesson Header -->
    <div class="card mb-4">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}">{{ Str::limit($course->title, 30) }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.lessons.index', $course) }}">Lessons</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($lesson->title, 30) }}</li>
                </ol>
            </nav>
            
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <div class="lesson-order-badge bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3" 
                             style="width: 45px; height: 45px; font-size: 18px; font-weight: 600; flex-shrink: 0;">
                            {{ $lesson->order }}
                        </div>
                        <div>
                            <h1 class="h3 mb-0">{{ $lesson->title }}</h1>
                            <p class="text-muted mb-0">{{ $lesson->description }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="btn-group" role="group">
                        <a href="{{ route('teacher.courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i>Edit Lesson
                        </a>
                        <a href="{{ route('teacher.assignments.create', [$course, $lesson]) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add Assignment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Video Section -->
            @if($lesson->video_url)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-video me-2"></i>Lesson Video
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $displayUrl = $lesson->getDisplayVideoUrl();
                    @endphp
                    
                    @if(filter_var($lesson->video_url, FILTER_VALIDATE_URL))
                        @if(str_contains($lesson->video_url, 'youtube.com') || str_contains($lesson->video_url, 'youtu.be'))
                            <!-- YouTube embed -->
                            @php
                                $videoId = '';
                                if (str_contains($lesson->video_url, 'youtube.com/watch?v=')) {
                                    $videoId = substr($lesson->video_url, strpos($lesson->video_url, 'v=') + 2, 11);
                                } elseif (str_contains($lesson->video_url, 'youtu.be/')) {
                                    $videoId = substr($lesson->video_url, strrpos($lesson->video_url, '/') + 1, 11);
                                }
                            @endphp
                            @if($videoId)
                                <div class="ratio ratio-16x9">
                                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                                            allowfullscreen></iframe>
                                </div>
                            @else
                                <a href="{{ $lesson->video_url }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>Watch Video
                                </a>
                            @endif
                        @elseif(str_contains($lesson->video_url, 'vimeo.com'))
                            <!-- Vimeo embed -->
                            @php
                                $videoId = substr($lesson->video_url, strrpos($lesson->video_url, '/') + 1);
                            @endphp
                            <div class="ratio ratio-16x9">
                                <iframe src="https://player.vimeo.com/video/{{ $videoId }}" 
                                        allowfullscreen></iframe>
                            </div>
                        @else
                            <a href="{{ $lesson->video_url }}" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-external-link-alt me-1"></i>Watch Video
                            </a>
                        @endif
                    @else
                        <!-- Local video file -->
                        <video controls class="w-100" style="max-height: 500px;" controlsList="nodownload">
                            <source src="{{ $displayUrl }}" type="video/mp4">
                            <source src="{{ $displayUrl }}" type="video/webm">
                            <source src="{{ $displayUrl }}" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                        
                        @if(!file_exists(public_path('storage/' . $lesson->video_url)))
                            <div class="alert alert-warning mt-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Video file not found!</strong> The video may have been moved or deleted.
                                <br><small class="text-muted">Path: {{ $lesson->video_url }}</small>
                            </div>
                        @endif
                    @endif
                    
                    @if($lesson->duration)
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>Duration: {{ $lesson->duration }} minutes
                            </small>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Lesson Content -->
            @if($lesson->content)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-text me-2"></i>Lesson Content
                    </h5>
                </div>
                <div class="card-body">
                    <div class="lesson-content">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Learning Objectives -->
            @if($lesson->learning_objectives)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bullseye me-2"></i>Learning Objectives
                    </h5>
                </div>
                <div class="card-body">
                    <div class="learning-objectives">
                        {!! nl2br(e($lesson->learning_objectives)) !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Assignments Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>Assignments ({{ $assignments->count() }})
                    </h5>
                    <a href="{{ route('teacher.assignments.create', [$course, $lesson]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Assignment
                    </a>
                </div>
                <div class="card-body">
                    @if($assignments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($assignments as $assignment)
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="mb-0 me-2">{{ $assignment->title }}</h6>
                                        <span class="badge bg-{{ $assignment->assignment_type == 'quiz' ? 'info' : ($assignment->assignment_type == 'project' ? 'success' : 'secondary') }}">
                                            {{ ucfirst($assignment->assignment_type) }}
                                        </span>
                                        <span class="badge bg-outline-{{ $assignment->difficulty_level == 'beginner' ? 'success' : ($assignment->difficulty_level == 'intermediate' ? 'warning' : 'danger') }} ms-2">
                                            {{ ucfirst($assignment->difficulty_level) }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-2 small">{{ Str::limit($assignment->description, 150) }}</p>
                                    <div class="d-flex gap-3 small text-muted">
                                        @if($assignment->points)
                                            <span><i class="fas fa-star me-1"></i>{{ $assignment->points }} points</span>
                                        @endif
                                        @if($assignment->estimated_time)
                                            <span><i class="fas fa-clock me-1"></i>{{ $assignment->estimated_time }} min</span>
                                        @endif
                                        @if($assignment->due_date)
                                            <span><i class="fas fa-calendar me-1"></i>Due: {{ $assignment->due_date->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('teacher.assignments.submissions', $assignment) }}" 
                                       class="btn btn-success btn-sm" title="View Submissions">
                                        <i class="fas fa-users"></i> Submissions
                                    </a>
                                    <a href="{{ route('teacher.assignments.show', [$course, $lesson, $assignment]) }}" 
                                       class="btn btn-outline-primary btn-sm" title="View Assignment">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('teacher.assignments.edit', [$course, $lesson, $assignment]) }}" 
                                       class="btn btn-outline-secondary btn-sm" title="Edit Assignment">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            onclick="deleteAssignment({{ $assignment->id }}, '{{ addslashes($assignment->title) }}')" 
                                            title="Delete Assignment">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No assignments yet</h6>
                            <p class="text-muted small mb-3">Create assignments to help students practice what they've learned</p>
                            <a href="{{ route('teacher.assignments.create', [$course, $lesson]) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Create First Assignment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Lesson Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Lesson Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <strong>Course:</strong><br>
                            <a href="{{ route('teacher.courses.show', $course) }}" class="text-decoration-none">
                                {{ $course->title }}
                            </a>
                        </div>
                        <div class="col-6">
                            <strong>Order:</strong><br>
                            <span class="text-muted">Lesson {{ $lesson->order }}</span>
                        </div>
                        <div class="col-6">
                            <strong>Duration:</strong><br>
                            <span class="text-muted">{{ $lesson->duration ?? 'Not set' }} min</span>
                        </div>
                        <div class="col-12">
                            <strong>Video:</strong><br>
                            <span class="text-{{ $lesson->video_url ? 'success' : 'warning' }}">
                                {{ $lesson->video_url ? 'Available' : 'Not uploaded' }}
                            </span>
                        </div>
                        <div class="col-12">
                            <strong>Created:</strong><br>
                            <small class="text-muted">{{ $lesson->created_at->format('M d, Y \a\t g:i A') }}</small>
                        </div>
                        <div class="col-12">
                            <strong>Last Updated:</strong><br>
                            <small class="text-muted">{{ $lesson->updated_at->format('M d, Y \a\t g:i A') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit Lesson
                        </a>
                        
                        <a href="{{ route('teacher.assignments.create', [$course, $lesson]) }}" class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i>Add Assignment
                        </a>
                        
                        @if(!$lesson->video_url)
                        <a href="{{ route('teacher.courses.lessons.edit', [$course, $lesson]) }}#video-section" class="btn btn-outline-warning">
                            <i class="fas fa-video me-2"></i>Upload Video
                        </a>
                        @endif
                        
                        <hr>
                        
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i>Delete Lesson
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-compass me-2"></i>Navigation
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.courses.lessons.index', $course) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Lessons
                        </a>
                        
                        @if($lesson->previous())
                        <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson->previous()]) }}" class="btn btn-outline-info">
                            <i class="fas fa-chevron-left me-2"></i>Previous Lesson
                        </a>
                        @endif
                        
                        @if($lesson->next())
                        <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson->next()]) }}" class="btn btn-outline-info">
                            <i class="fas fa-chevron-right me-2"></i>Next Lesson
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Lesson Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h6>Are you sure you want to delete this lesson?</h6>
                </div>
                
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This action cannot be undone. The lesson, its video, and all {{ $assignments->count() }} assignment(s) will be permanently deleted.
                </div>
                
                <div class="bg-light rounded p-3">
                    <strong>Lesson:</strong> {{ $lesson->title }}<br>
                    <strong>Assignments:</strong> {{ $assignments->count() }} assignment(s)<br>
                    <strong>Video:</strong> {{ $lesson->video_url ? 'Will be deleted' : 'None' }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('teacher.courses.lessons.destroy', [$course, $lesson]) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Lesson
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Assignment Modal -->
<div class="modal fade" id="deleteAssignmentModal" tabindex="-1" aria-labelledby="deleteAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAssignmentModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete Assignment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-tasks fa-3x text-danger mb-3"></i>
                    <h6>Are you sure you want to delete this assignment?</h6>
                </div>
                
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This action cannot be undone. All student submissions will also be deleted.
                </div>
                
                <div class="bg-light rounded p-3">
                    <strong>Assignment:</strong> <span id="assignmentTitle"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteAssignmentForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Assignment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete assignment functionality
    window.deleteAssignment = function(assignmentId, assignmentTitle) {
        document.getElementById('assignmentTitle').textContent = assignmentTitle;
        document.getElementById('deleteAssignmentForm').action = 
            `{{ route('teacher.assignments.store', [$course, $lesson]) }}/${assignmentId}`;
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteAssignmentModal'));
        deleteModal.show();
    };
});
</script>
@endpush