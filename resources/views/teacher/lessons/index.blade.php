@extends('teacher.layouts.app')

@section('title', 'Course Lessons - ' . $course->title)
@section('page-title', 'Course Lessons')

@section('content')
<div class="container-fluid">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Course Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Courses</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}">{{ Str::limit($course->title, 30) }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Lessons</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-1">{{ $course->title }}</h1>
                    <p class="text-muted mb-0">Manage lessons and content for your course</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                        <i class="fas fa-plus me-2"></i>Add New Lesson
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Lessons List -->
        <div class="col-lg-8">
            <!-- Lessons Overview -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list-ol me-2"></i>Course Lessons ({{ $lessons->count() }})
                    </h5>
                    @if($lessons->count() > 0)
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="reorderButton">
                            <i class="fas fa-sort me-1"></i>Reorder Lessons
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($lessons->count() > 0)
                        <div id="lessons-container">
                            @foreach($lessons as $lesson)
                            <div class="lesson-item border rounded p-3 mb-3" data-lesson-id="{{ $lesson->id }}">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <div class="lesson-order-badge bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                             style="width: 30px; height: 30px; font-size: 0.9rem;">
                                            {{ $lesson->order }}
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <h6 class="mb-1">
                                            <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}" 
                                               class="text-decoration-none">
                                                {{ $lesson->title }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-2">{{ Str::limit($lesson->description, 100) }}</p>
                                        <div class="d-flex gap-3">
                                            @if($lesson->video_url)
                                                <small class="text-success">
                                                    <i class="fas fa-video me-1"></i>Video Available
                                                </small>
                                            @else
                                                <small class="text-warning">
                                                    <i class="fas fa-video-slash me-1"></i>No Video
                                                </small>
                                            @endif
                                            
                                            @if($lesson->duration)
                                                <small class="text-info">
                                                    <i class="fas fa-clock me-1"></i>{{ $lesson->duration }} min
                                                </small>
                                            @endif
                                            
                                            <small class="text-muted">
                                                <i class="fas fa-tasks me-1"></i>{{ $lesson->assignments_count }} Assignment(s)
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}" 
                                               class="btn btn-outline-primary btn-sm" title="View Lesson">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.courses.lessons.edit', [$course, $lesson]) }}" 
                                               class="btn btn-outline-secondary btn-sm" title="Edit Lesson">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('teacher.assignments.create', [$course, $lesson]) }}" 
                                               class="btn btn-outline-success btn-sm" title="Add Assignment">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteLesson({{ $lesson->id }}, '{{ addslashes($lesson->title) }}')" title="Delete Lesson">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No lessons created yet</h5>
                            <p class="text-muted mb-4">Start building your course by creating your first lesson with video content and assignments.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                                <i class="fas fa-plus me-2"></i>Create First Lesson
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Course Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-book-open text-primary"></i>
                                </div>
                                <h4 class="mb-0">{{ $lessons->count() }}</h4>
                                <small class="text-muted">Lessons</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-tasks text-success"></i>
                                </div>
                                <h4 class="mb-0">{{ $lessons->sum('assignments_count') }}</h4>
                                <small class="text-muted">Assignments</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-video text-info"></i>
                                </div>
                                <h4 class="mb-0">{{ $lessons->whereNotNull('video_url')->count() }}</h4>
                                <small class="text-muted">With Videos</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-clock text-warning"></i>
                                </div>
                                <h4 class="mb-0">{{ $lessons->sum('duration') ?? 0 }}</h4>
                                <small class="text-muted">Total Minutes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                            <i class="fas fa-plus me-2"></i>Add New Lesson
                        </button>
                        <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Course
                        </a>
                        <a href="{{ route('teacher.courses.edit', $course) }}" class="btn btn-outline-info">
                            <i class="fas fa-edit me-2"></i>Edit Course Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Help Guide -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>Need Help?
                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion accordion-flush" id="helpAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingVideo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapseVideo" aria-expanded="false" aria-controls="collapseVideo">
                                    <i class="fas fa-video me-2"></i>Video Upload Tips
                                </button>
                            </h2>
                            <div id="collapseVideo" class="accordion-collapse collapse" aria-labelledby="headingVideo" 
                                 data-bs-parent="#helpAccordion">
                                <div class="accordion-body small">
                                    <ul class="mb-0">
                                        <li>Use MP4 format for best compatibility</li>
                                        <li>Keep file size under 500MB</li>
                                        <li>Use 1080p or 720p resolution</li>
                                        <li>Add clear audio and good lighting</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAssignment">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapseAssignment" aria-expanded="false" aria-controls="collapseAssignment">
                                    <i class="fas fa-tasks me-2"></i>Assignment Best Practices
                                </button>
                            </h2>
                            <div id="collapseAssignment" class="accordion-collapse collapse" aria-labelledby="headingAssignment" 
                                 data-bs-parent="#helpAccordion">
                                <div class="accordion-body small">
                                    <ul class="mb-0">
                                        <li>Write clear, specific instructions</li>
                                        <li>Set realistic deadlines</li>
                                        <li>Include examples when possible</li>
                                        <li>Provide assessment criteria</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
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
                    <strong>Warning:</strong> This action cannot be undone. The lesson, its video, and all associated assignments will be permanently deleted.
                </div>
                
                <div class="bg-light rounded p-3">
                    <strong>Lesson:</strong> <span id="lessonTitle"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete lesson functionality
    window.deleteLesson = function(lessonId, lessonTitle) {
        document.getElementById('lessonTitle').textContent = lessonTitle;
        document.getElementById('deleteForm').action = 
            `{{ route('teacher.courses.lessons.index', $course) }}/${lessonId}`;
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    };

    // Lesson reordering functionality
    const reorderButton = document.getElementById('reorderButton');
    const lessonsContainer = document.getElementById('lessons-container');
    let isReordering = false;

    if (reorderButton) {
        reorderButton.addEventListener('click', function() {
            if (!isReordering) {
                enableReordering();
            } else {
                disableReordering();
            }
        });
    }

    function enableReordering() {
        isReordering = true;
        reorderButton.innerHTML = '<i class="fas fa-save me-1"></i>Save Order';
        reorderButton.className = 'btn btn-success btn-sm';
        
        // Make lessons sortable
        if (typeof Sortable !== 'undefined') {
            new Sortable(lessonsContainer, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    // Update order numbers visually
                    updateOrderNumbers();
                }
            });
        }
        
        // Add visual cues
        const lessonItems = document.querySelectorAll('.lesson-item');
        lessonItems.forEach(item => {
            item.style.cursor = 'move';
            item.classList.add('border-primary');
        });
    }

    function disableReordering() {
        // Save the new order
        const lessonIds = Array.from(document.querySelectorAll('.lesson-item')).map(item => 
            item.getAttribute('data-lesson-id')
        );
        
        // Send AJAX request to save order
        fetch(`{{ route('teacher.courses.lessons.reorder', $course) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ lesson_ids: lessonIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>Lesson order updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.container-fluid').firstChild);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to save lesson order. Please try again.');
        });
        
        isReordering = false;
        reorderButton.innerHTML = '<i class="fas fa-sort me-1"></i>Reorder Lessons';
        reorderButton.className = 'btn btn-outline-secondary btn-sm';
        
        // Remove visual cues
        const lessonItems = document.querySelectorAll('.lesson-item');
        lessonItems.forEach(item => {
            item.style.cursor = 'default';
            item.classList.remove('border-primary');
        });
    }

    function updateOrderNumbers() {
        const lessonItems = document.querySelectorAll('.lesson-item');
        lessonItems.forEach((item, index) => {
            const badge = item.querySelector('.lesson-order-badge');
            badge.textContent = index + 1;
        });
    }
});
</script>

<style>
.sortable-ghost {
    opacity: 0.5;
}

.lesson-item {
    transition: all 0.3s ease;
}

.lesson-item:hover {
    background-color: #f8f9fa;
}
</style>
@endpush