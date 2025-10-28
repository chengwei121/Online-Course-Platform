@extends('teacher.layouts.app')

@section('title', $assignment->title)
@section('page-title', 'Assignment Details')

@section('content')
<div class="container-fluid">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Courses</a></li>
            <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}">{{ Str::limit($course->title, 30) }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('teacher.courses.lessons.index', $course) }}">Lessons</a></li>
            <li class="breadcrumb-item"><a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}">{{ Str::limit($lesson->title, 30) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($assignment->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Assignment Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <!-- Assignment Type Badge -->
                                @php
                                    $typeIcons = [
                                        'quiz' => 'fa-clipboard-question',
                                        'project' => 'fa-rocket',
                                        'essay' => 'fa-file-alt',
                                        'coding' => 'fa-code',
                                        'presentation' => 'fa-presentation'
                                    ];
                                    $typeColors = [
                                        'quiz' => 'primary',
                                        'project' => 'success',
                                        'essay' => 'info',
                                        'coding' => 'warning',
                                        'presentation' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $typeColors[$assignment->assignment_type] ?? 'secondary' }} fs-6">
                                    <i class="fas {{ $typeIcons[$assignment->assignment_type] ?? 'fa-tasks' }} me-1"></i>
                                    {{ ucfirst($assignment->assignment_type) }}
                                </span>

                                <!-- Difficulty Badge -->
                                @php
                                    $difficultyColors = [
                                        'beginner' => 'success',
                                        'intermediate' => 'warning',
                                        'advanced' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $difficultyColors[$assignment->difficulty_level] ?? 'secondary' }} fs-6">
                                    <i class="fas fa-signal me-1"></i>
                                    {{ ucfirst($assignment->difficulty_level) }}
                                </span>
                            </div>

                            <h1 class="h3 mb-2">{{ $assignment->title }}</h1>
                            <p class="text-muted mb-0">{{ $assignment->description }}</p>
                        </div>

                        <div class="btn-group" role="group">
                            <a href="{{ route('teacher.assignments.edit', [$course, $lesson, $assignment]) }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="deleteAssignment()">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list-ol me-2"></i>Instructions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="assignment-instructions">
                        {!! nl2br(e($assignment->instructions)) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Assignment Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Assignment Info
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Points</small>
                                    <strong>{{ $assignment->points }}</strong>
                                </div>
                            </div>
                        </li>

                        @if($assignment->due_date)
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning rounded p-2 me-3">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Due Date</small>
                                    @if($assignment->due_date)
                                        <strong>{{ $assignment->due_date->format('M d, Y h:i A') }}</strong>
                                        @php
                                            $now = now();
                                            $dueDate = $assignment->due_date;
                                            $diff = $now->diffInDays($dueDate, false);
                                        @endphp
                                        @if($diff < 0)
                                            <small class="text-danger d-block">Overdue</small>
                                        @elseif($diff == 0)
                                            <small class="text-warning d-block">Due today</small>
                                        @else
                                            <small class="text-muted d-block">{{ $diff }} day(s) remaining</small>
                                        @endif
                                    @else
                                        <strong>No deadline</strong>
                                        <small class="text-muted d-block">Students can submit anytime</small>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @else
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-secondary bg-opacity-10 text-secondary rounded p-2 me-3">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Due Date</small>
                                    <strong>No deadline</strong>
                                </div>
                            </div>
                        </li>
                        @endif

                        @if($assignment->estimated_time)
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-info bg-opacity-10 text-info rounded p-2 me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Estimated Time</small>
                                    <strong>{{ $assignment->estimated_time }} minutes</strong>
                                </div>
                            </div>
                        </li>
                        @endif

                        <li class="mb-0">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success bg-opacity-10 text-success rounded p-2 me-3">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Lesson</small>
                                    <strong>{{ Str::limit($lesson->title, 20) }}</strong>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.assignments.submissions', $assignment) }}" 
                           class="btn btn-success btn-lg">
                            <i class="fas fa-users me-2"></i>View All Submissions
                        </a>
                        
                        <hr>
                        
                        <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Lesson
                        </a>
                        <a href="{{ route('teacher.assignments.create', [$course, $lesson]) }}" 
                           class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i>Create Another Assignment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Assignment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this assignment?</p>
                <p class="mb-0"><strong>{{ $assignment->title }}</strong></p>
                <p class="text-danger mt-2 mb-0">
                    <i class="fas fa-warning me-1"></i>This action cannot be undone!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('teacher.assignments.destroy', [$course, $lesson, $assignment]) }}" 
                      method="POST" 
                      class="d-inline">
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

@push('scripts')
<script>
    function deleteAssignment() {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endpush

@push('styles')
<style>
    .icon-box {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .assignment-instructions {
        line-height: 1.8;
        white-space: pre-wrap;
    }
</style>
@endpush
@endsection
