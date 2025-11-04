@extends('teacher.layouts.app')

@section('title', $course->title)
@section('page-title', 'Course Details')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">My Courses</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($course->title, 40) }}</li>
        </ol>
    </nav>

    <!-- Course Overview Section -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row">
                <!-- Course Thumbnail -->
                <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
                    @if($course->thumbnail)
                        <img src="{{ $course->thumbnail }}" class="img-fluid rounded shadow-sm" 
                             alt="{{ $course->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" 
                             style="width: 100%; height: 200px;">
                            <i class="fas fa-book fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <!-- Course Status Badge -->
                    <div class="mt-2 text-center">
                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'warning' }} px-3 py-2">
                            <i class="fas fa-{{ $course->status === 'published' ? 'check-circle' : 'clock' }} me-1"></i>
                            {{ ucfirst($course->status) }}
                        </span>
                    </div>
                </div>

                <!-- Course Information -->
                <div class="col-md-8 col-lg-9">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h3 mb-2 text-dark">{{ $course->title }}</h1>
                            <p class="text-muted mb-0">{{ $course->description }}</p>
                        </div>
                        
                        <!-- Course Actions -->
                        <div class="course-actions d-flex flex-wrap gap-2 align-items-center">
                            <!-- Edit Button -->
                            <a href="{{ route('teacher.courses.edit', $course) }}" 
                               class="btn btn-primary btn-action" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top"
                               title="Edit course details and content">
                                <i class="fas fa-edit"></i>
                                <span class="btn-text">Edit</span>
                            </a>
                            
                            <!-- Toggle Status Button -->
                            <form method="POST" action="{{ route('teacher.courses.toggle-status', $course) }}" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-{{ $course->status === 'published' ? 'warning' : 'success' }} btn-action"
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top"
                                        title="{{ $course->status === 'published' ? 'Hide course from students' : 'Make course available to students' }}">
                                    <i class="fas fa-{{ $course->status === 'published' ? 'eye-slash' : 'eye' }}"></i>
                                    <span class="btn-text">{{ $course->status === 'published' ? 'Unpublish' : 'Publish' }}</span>
                                </button>
                            </form>
                            
                            <!-- Delete Button -->
                            <button type="button" 
                                    class="btn btn-outline-danger btn-action" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"
                                    data-bs-placement="top"
                                    data-bs-tooltip="tooltip" 
                                    title="Permanently delete this course">
                                <i class="fas fa-trash-alt"></i>
                                <span class="btn-text">Delete</span>
                            </button>
                        </div>
                    </div>

                    <!-- Course Statistics -->
                    <div class="row g-3 mb-3">
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-primary bg-opacity-10 text-center">
                                <div class="card-body py-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-users fa-2x text-primary me-2"></i>
                                        <div>
                                            <h4 class="mb-0 text-primary">{{ $course->enrollments_count ?? 0 }}</h4>
                                            <small class="text-muted">Students</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-info bg-opacity-10 text-center">
                                <div class="card-body py-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-book-open fa-2x text-info me-2"></i>
                                        <div>
                                            <h4 class="mb-0 text-info">{{ $course->lessons_count ?? 0 }}</h4>
                                            <small class="text-muted">Lessons</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-success bg-opacity-10 text-center">
                                <div class="card-body py-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-coins fa-2x text-success me-2"></i>
                                        <div>
                                            @if($course->price == 0)
                                                <h4 class="mb-0 text-success">Free</h4>
                                            @else
                                                <h4 class="mb-0 text-success">RM{{ number_format($course->price, 0) }}</h4>
                                            @endif
                                            <small class="text-muted">Price</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-warning bg-opacity-10 text-center">
                                <div class="card-body py-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-star fa-2x text-warning me-2"></i>
                                        <div>
                                            <h4 class="mb-0 text-warning">{{ number_format($course->average_rating ?? 0, 1) }}</h4>
                                            <small class="text-muted">Rating</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Details -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-layer-group text-primary me-2"></i>
                                <span class="text-muted me-2">Category:</span>
                                <span class="fw-semibold">{{ $course->category->name ?? 'Uncategorized' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-signal text-info me-2"></i>
                                <span class="text-muted me-2">Level:</span>
                                <span class="fw-semibold">{{ ucfirst($course->level ?? 'Beginner') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-warning me-2"></i>
                                <span class="text-muted me-2">Duration:</span>
                                <span class="fw-semibold">{{ $course->learning_hours ? $course->learning_hours . ' hours' : 'Not specified' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar text-secondary me-2"></i>
                                <span class="text-muted me-2">Created:</span>
                                <span class="fw-semibold">{{ $course->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Management Tabs -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs" id="courseManagementTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="lessons-tab" data-bs-toggle="tab" data-bs-target="#lessons" 
                            type="button" role="tab" aria-controls="lessons" aria-selected="true">
                        <i class="fas fa-book-open me-2"></i>Lessons
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" 
                            type="button" role="tab" aria-controls="students" aria-selected="false">
                        <i class="fas fa-users me-2"></i>Students
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" 
                            type="button" role="tab" aria-controls="analytics" aria-selected="false">
                        <i class="fas fa-chart-bar me-2"></i>Analytics
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="courseManagementTabContent">
                <!-- Lessons Tab -->
                <div class="tab-pane fade show active" id="lessons" role="tabpanel" aria-labelledby="lessons-tab">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Course Lessons</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                            <i class="fas fa-plus me-1"></i>Add New Lesson
                        </button>
                    </div>

                    @if($lessons->count() > 0)
                        <div class="list-group">
                            @foreach($lessons as $lesson)
                            <div class="list-group-item list-group-item-action">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="lesson-order-badge bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 35px; height: 35px; font-size: 14px;">
                                            {{ $lesson->order }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $lesson->title }}</h6>
                                            <small class="text-muted">{{ $lesson->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1 text-muted">{{ Str::limit($lesson->description, 100) }}</p>
                                        <small class="text-muted">
                                            @if($lesson->duration)
                                                <i class="fas fa-clock me-1"></i>{{ $lesson->duration }} 
                                                {{ $lesson->duration == 1 ? 'minute' : 'minutes' }}
                                            @endif
                                            @if($lesson->video_url)
                                                <i class="fas fa-video ms-3 me-1"></i>Video Available
                                            @endif
                                        </small>
                                    </div>
                                    <div class="col-auto">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}" 
                                               class="btn btn-outline-primary btn-sm"
                                               data-bs-toggle="tooltip" title="View Lesson">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.courses.lessons.edit', [$course, $lesson]) }}" 
                                               class="btn btn-outline-secondary btn-sm"
                                               data-bs-toggle="tooltip" title="Edit Lesson">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteLessonModal{{ $lesson->id }}"
                                                    data-bs-tooltip="tooltip" 
                                                    title="Delete Lesson">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Lesson Modal -->
                            <div class="modal fade" id="deleteLessonModal{{ $lesson->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-exclamation-triangle me-2"></i>Delete Lesson
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete the lesson "<strong>{{ $lesson->title }}</strong>"?</p>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                This action cannot be undone. All lesson content and assignments will be permanently deleted.
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
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5>No lessons yet</h5>
                            <p class="text-muted mb-4">Start building your course by adding lessons.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                                <i class="fas fa-plus me-1"></i>Add Your First Lesson
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Students Tab -->
                <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Enrolled Students</h5>
                        <div class="text-muted">
                            <i class="fas fa-users me-1"></i>{{ $course->enrollments_count ?? 0 }} Total Students
                        </div>
                    </div>

                    @if($enrollments->count() > 0)
                        <div class="row">
                            @foreach($enrollments as $enrollment)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="avatar mb-3">
                                            @if($enrollment->user->avatar)
                                                <img src="{{ $enrollment->user->avatar }}" class="rounded-circle" 
                                                     width="60" height="60" alt="{{ $enrollment->user->name }}">
                                            @else
                                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-user fa-lg"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <h6 class="card-title mb-1">{{ $enrollment->user->name }}</h6>
                                        <p class="card-text text-muted small mb-2">{{ $enrollment->user->email }}</p>
                                        <div class="small text-muted">
                                            <div>Enrolled: {{ $enrollment->created_at->format('M d, Y') }}</div>
                                            <div class="mt-1">
                                                <span class="badge bg-{{ $enrollment->status === 'active' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($enrollment->status ?? 'active') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($course->enrollments_count > 10)
                            <div class="text-center mt-4">
                                <a href="#" class="btn btn-outline-primary">
                                    <i class="fas fa-users me-1"></i>View All Students
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5>No students enrolled yet</h5>
                            <p class="text-muted">Once students enroll in your course, they will appear here.</p>
                        </div>
                    @endif
                </div>

                <!-- Analytics Tab -->
                <div class="tab-pane fade" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
                    <div class="row">
                        <!-- Revenue Overview -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 bg-success bg-opacity-10">
                                <div class="card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-coins fa-2x text-success me-3"></i>
                                        <div>
                                            @if($course->price == 0)
                                                <h3 class="mb-0 text-success">Free Course</h3>
                                            @else
                                                <h3 class="mb-0 text-success">RM{{ number_format(($course->enrollments_count ?? 0) * $course->price, 2) }}</h3>
                                            @endif
                                            <p class="mb-0 text-muted">Total Revenue</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Completion Rate -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 bg-info bg-opacity-10">
                                <div class="card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-chart-line fa-2x text-info me-3"></i>
                                        <div>
                                            <h3 class="mb-0 text-info">75%</h3>
                                            <p class="mb-0 text-muted">Completion Rate</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Course Performance Chart Placeholder -->
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Enrollment Trend</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center py-5 text-muted">
                                        <i class="fas fa-chart-area fa-3x mb-3"></i>
                                        <p>Enrollment analytics chart will be displayed here</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- YouTube-Style Add Lesson Modal -->
    <div class="modal fade" id="addLessonModal" tabindex="-1" aria-labelledby="addLessonModalLabel" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <!-- Modal Header -->
                <div class="modal-header bg-gradient-primary text-white border-0 py-2">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon me-2">
                            <i class="fas fa-video fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="modal-title mb-0" id="addLessonModalLabel">Create Lesson</h6>
                            <small class="opacity-75">Add content to your course</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-0">
                    <form id="addLessonForm" method="POST" action="{{ route('teacher.courses.lessons.store', $course) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Progress Steps -->
                        <div class="progress-container bg-light border-bottom">
                            <div class="container-fluid py-2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="step-indicator active" data-step="1">
                                                <div class="step-circle">
                                                    <i class="fas fa-info-circle"></i>
                                                </div>
                                                <span class="step-label">Basic Info</span>
                                            </div>
                                            <div class="step-line"></div>
                                            <div class="step-indicator" data-step="2">
                                                <div class="step-circle">
                                                    <i class="fas fa-video"></i>
                                                </div>
                                                <span class="step-label">Video Content</span>
                                            </div>
                                            <div class="step-line"></div>
                                            <div class="step-indicator" data-step="3">
                                                <div class="step-circle">
                                                    <i class="fas fa-tasks"></i>
                                                </div>
                                                <span class="step-label">Assignment</span>
                                            </div>
                                            <div class="step-line"></div>
                                            <div class="step-indicator" data-step="4">
                                                <div class="step-circle">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <span class="step-label">Review</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step Content -->
                        <div class="step-content">
                            <!-- Step 1: Basic Information -->
                            <div class="step-pane active" id="step-1">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="text-center mb-2">
                                                <i class="fas fa-edit text-primary"></i>
                                                <h6 class="mb-0 mt-1">Lesson Details</h6>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group mb-2">
                                                        <label for="lesson_title" class="form-label fw-bold small">
                                                            <i class="fas fa-heading me-1 text-primary"></i>Lesson Title
                                                        </label>
                                                        <input type="text" class="form-control form-control-sm" id="lesson_title" name="title" 
                                                               placeholder="Enter lesson title..." required>
                                                    </div>

                                                    <div class="form-group mb-2">
                                                        <label for="lesson_description" class="form-label fw-bold small">
                                                            <i class="fas fa-align-left me-1 text-primary"></i>Description
                                                        </label>
                                                        <textarea class="form-control form-control-sm" id="lesson_description" name="description" rows="2" 
                                                                  placeholder="Brief description..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-2">
                                                        <label for="lesson_duration" class="form-label fw-bold small">
                                                            <i class="fas fa-clock me-1 text-primary"></i>Duration (minutes)
                                                        </label>
                                                        <input type="number" class="form-control form-control-sm" id="lesson_duration" name="duration" 
                                                               placeholder="60" min="1" step="1">
                                                        <small class="text-muted">Enter duration in minutes</small>
                                                    </div>

                                                    <div class="form-group mb-2">
                                                        <label for="lesson_order" class="form-label fw-bold small">
                                                            <i class="fas fa-sort-numeric-up me-1 text-primary"></i>Order
                                                        </label>
                                                        <input type="number" class="form-control form-control-sm" id="lesson_order" name="order" 
                                                               value="{{ ($lessons->count() ?? 0) + 1 }}" min="1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Video Content -->
                            <div class="step-pane" id="step-2">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="text-center mb-2">
                                                <i class="fas fa-video text-primary"></i>
                                                <h6 class="mb-0 mt-1">Video Content</h6>
                                            </div>

                                            <!-- Video Upload Options -->
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body p-2">
                                                    <ul class="nav nav-pills nav-justified mb-2" id="videoUploadTabs" role="tablist">
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link active small py-1" id="upload-tab" data-bs-toggle="pill" 
                                                                    data-bs-target="#upload-content" type="button" role="tab">
                                                                <i class="fas fa-upload me-1"></i>Upload
                                                            </button>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link small py-1" id="url-tab" data-bs-toggle="pill" 
                                                                    data-bs-target="#url-content" type="button" role="tab">
                                                                <i class="fas fa-link me-1"></i>URL
                                                            </button>
                                                        </li>
                                                    </ul>

                                                    <div class="tab-content" id="videoUploadTabContent">
                                                        <!-- Upload Video Tab -->
                                                        <div class="tab-pane fade show active" id="upload-content" role="tabpanel">
                                                            <div class="video-upload-zone" id="videoUploadZone">
                                                                <input type="file" id="video_file" name="video" accept="video/*" hidden>
                                                                <div class="upload-placeholder text-center py-4">
                                                                    <div class="upload-icon mb-3">
                                                                        <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                                                                    </div>
                                                                    <h6>Drag and drop your video here</h6>
                                                                    <p class="text-muted mb-3">or click to browse files</p>
                                                                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('video_file').click()">
                                                                        <i class="fas fa-folder-open me-1"></i>Choose Video File
                                                                    </button>
                                                                    <div class="mt-3">
                                                                        <small class="text-muted">
                                                                            MP4, AVI, MOV, WMV, FLV • Max 500MB
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Video Preview -->
                                                                <div class="video-preview d-none">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-md-5">
                                                                            <video class="w-100 rounded" controls style="max-height: 180px;">
                                                                                <source id="videoPreviewSource" src="" type="">
                                                                                Your browser does not support the video tag.
                                                                            </video>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <div class="video-info">
                                                                                <h6 class="mb-2" id="videoFileName"></h6>
                                                                                <div class="text-muted">
                                                                                    <div class="mb-1">Size: <span id="videoFileSize"></span></div>
                                                                                    <div class="mb-2">Duration: <span id="videoDuration">Calculating...</span></div>
                                                                                </div>
                                                                                <div class="mt-3">
                                                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearVideoUpload()">
                                                                                        <i class="fas fa-trash me-1"></i>Remove
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Upload Progress -->
                                                                <div class="upload-progress d-none">
                                                                    <div class="progress mb-3">
                                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                                             role="progressbar" style="width: 0%"></div>
                                                                    </div>
                                                                    <div class="text-center">
                                                                        <small class="text-muted">Uploading... <span class="upload-percentage">0%</span></small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Video URL Tab -->
                                                        <div class="tab-pane fade" id="url-content" role="tabpanel">
                                                            <div class="form-group mb-4">
                                                                <label for="video_url" class="form-label fw-bold">
                                                                    <i class="fas fa-link me-2"></i>Video URL
                                                                </label>
                                                                <input type="url" class="form-control" id="video_url" name="video_url" 
                                                                       placeholder="https://www.youtube.com/watch?v=...">
                                                                <div class="form-text">
                                                                    Support for YouTube, Vimeo, and direct video links.
                                                                </div>
                                                            </div>

                                                            <!-- URL Preview -->
                                                            <div class="url-preview mt-4 d-none">
                                                                <div class="card">
                                                                    <div class="card-body p-3">
                                                                        <h6 class="mb-3">Video Preview</h6>
                                                                        <div id="urlVideoPreview"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Assignment (Optional) -->
                            <div class="step-pane" id="step-3">
                                <div class="container-fluid py-4">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div class="text-center mb-4">
                                                <i class="fas fa-tasks fa-3x text-primary mb-3"></i>
                                                <h5>Add an assignment (Optional)</h5>
                                                <p class="text-muted">Help students practice what they've learned</p>
                                            </div>

                                            <div class="form-check form-switch mb-4">
                                                <input class="form-check-input" type="checkbox" id="includeAssignment">
                                                <label class="form-check-label fw-bold" for="includeAssignment">
                                                    Include an assignment with this lesson
                                                </label>
                                            </div>

                                            <div id="assignmentSection" class="d-none">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body">
                                                        <div class="form-group mb-3">
                                                            <label for="assignment_title" class="form-label fw-bold">
                                                                <i class="fas fa-clipboard-list me-2 text-primary"></i>Assignment Title
                                                            </label>
                                                            <input type="text" class="form-control" id="assignment_title" name="assignment_title" 
                                                                   placeholder="Enter assignment title...">
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="assignment_description" class="form-label fw-bold">
                                                                <i class="fas fa-align-left me-2 text-primary"></i>Instructions
                                                            </label>
                                                            <textarea class="form-control" id="assignment_description" name="assignment_description" 
                                                                      rows="4" placeholder="Provide clear instructions for the assignment..."></textarea>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="assignment_due_date" class="form-label fw-bold">
                                                                        <i class="fas fa-calendar me-2 text-primary"></i>Due Date
                                                                    </label>
                                                                    <input type="datetime-local" class="form-control" id="assignment_due_date" name="assignment_due_date">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="assignment_max_points" class="form-label fw-bold">
                                                                        <i class="fas fa-star me-2 text-primary"></i>Maximum Points
                                                                    </label>
                                                                    <input type="number" class="form-control" id="assignment_max_points" name="assignment_max_points" 
                                                                           value="100" min="1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Review -->
                            <div class="step-pane" id="step-4">
                                <div class="container-fluid py-4">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div class="text-center mb-4">
                                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                                <h5>Review your lesson</h5>
                                                <p class="text-muted">Make sure everything looks good before publishing</p>
                                            </div>

                                            <div class="review-section">
                                                <!-- Lesson Info Review -->
                                                <div class="card border-0 shadow-sm mb-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Lesson Information</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Title:</strong> <span id="review-title">-</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Duration:</strong> <span id="review-duration">-</span>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <strong>Description:</strong><br>
                                                            <span id="review-description" class="text-muted">-</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Video Review -->
                                                <div class="card border-0 shadow-sm mb-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i class="fas fa-video me-2 text-primary"></i>Video Content</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="review-video">No video selected</div>
                                                    </div>
                                                </div>

                                                <!-- Assignment Review -->
                                                <div class="card border-0 shadow-sm mb-3" id="review-assignment-card" style="display: none;">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i class="fas fa-tasks me-2 text-primary"></i>Assignment</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="review-assignment">No assignment included</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 bg-light py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" id="prevStepBtn" style="display: none;">
                            <i class="fas fa-arrow-left me-1"></i>Previous
                        </button>
                        <div class="ms-auto d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary btn-sm px-3" id="nextStepBtn">
                                Next <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                            <button type="submit" class="btn btn-success btn-sm px-3" id="submitLessonBtn" style="display: none;" form="addLessonForm">
                                <i class="fas fa-check me-1"></i>Create
                            </button>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Course Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Course
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                        <h6 class="fw-bold">Are you sure you want to delete this course?</h6>
                    </div>
                    
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>Warning:</strong> This action cannot be undone. All course data, lessons, and student progress will be permanently deleted.
                        </div>
                    </div>
                    
                    <div class="bg-light rounded p-3 mb-3">
                        <h6 class="fw-bold text-dark mb-2">Course Details:</h6>
                        <div class="row small">
                            <div class="col-6">
                                <strong>Title:</strong><br>
                                <span class="text-muted">{{ Str::limit($course->title, 30) }}</span>
                            </div>
                            <div class="col-6">
                                <strong>Students:</strong><br>
                                <span class="text-muted">{{ $course->enrollments_count ?? 0 }} enrolled</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label small" for="confirmDelete">
                            I understand that this action is permanent and cannot be undone
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                            <i class="fas fa-trash me-1"></i>Delete Course
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Course Action Buttons - Simplified Design */
.course-actions {
    min-width: fit-content;
}

.btn-action {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
    border-width: 1px;
    text-decoration: none;
    min-width: 100px;
    justify-content: center;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-action:active {
    transform: translateY(0);
    transition: transform 0.1s;
}

.btn-action i {
    font-size: 14px;
}

.btn-text {
    font-weight: 500;
}

/* Primary Button (Edit) - Subtle Blue */
.btn-action.btn-primary {
    background-color: #4f46e5;
    border-color: #4f46e5;
    color: white;
}

.btn-action.btn-primary:hover {
    background-color: #4338ca;
    border-color: #4338ca;
    color: white;
}

/* Success Button (Publish) - Subtle Green */
.btn-action.btn-success {
    background-color: #10b981;
    border-color: #10b981;
    color: white;
}

.btn-action.btn-success:hover {
    background-color: #059669;
    border-color: #059669;
    color: white;
}

/* Warning Button (Unpublish) - Subtle Orange */
.btn-action.btn-warning {
    background-color: #f59e0b;
    border-color: #f59e0b;
    color: white;
}

.btn-action.btn-warning:hover {
    background-color: #d97706;
    border-color: #d97706;
    color: white;
}

/* Outline Danger Button (Delete) - Subtle Red */
.btn-action.btn-outline-danger {
    background-color: transparent;
    border-color: #ef4444;
    color: #ef4444;
}

.btn-action.btn-outline-danger:hover {
    background-color: #ef4444;
    border-color: #ef4444;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .course-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-action {
        min-width: auto;
        width: 100%;
        margin-bottom: 8px;
    }
}

@media (max-width: 576px) {
    .btn-action {
        padding: 8px 14px;
        font-size: 13px;
        min-width: auto;
    }
    
    .btn-action i {
        font-size: 13px;
    }
}

/* Simple Tooltips */
.tooltip-inner {
    background-color: #374151;
    color: #f9fafb;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 12px;
    font-weight: 500;
    max-width: 200px;
}

.tooltip.bs-tooltip-top .tooltip-arrow::before {
    border-top-color: #374151;
}

.tooltip.bs-tooltip-bottom .tooltip-arrow::before {
    border-bottom-color: #374151;
}

.tooltip.bs-tooltip-start .tooltip-arrow::before {
    border-left-color: #374151;
}

.tooltip.bs-tooltip-end .tooltip-arrow::before {
    border-right-color: #374151;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle delete confirmation checkbox
    const confirmCheckbox = document.getElementById('confirmDelete');
    const deleteBtn = document.getElementById('deleteBtn');
    
    if (confirmCheckbox && deleteBtn) {
        confirmCheckbox.addEventListener('change', function() {
            deleteBtn.disabled = !this.checked;
        });
    }
    
    // Tab navigation with URL updates
    const tabButtons = document.querySelectorAll('#courseManagementTabs button[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            const target = e.target.getAttribute('data-bs-target');
            const tabName = target.replace('#', '');
            
            // Update URL without page reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.replaceState({}, '', url);
        });
    });
    
    // Load tab from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');
    if (activeTab) {
        const tabButton = document.querySelector(`#${activeTab}-tab`);
        if (tabButton) {
            const tab = new bootstrap.Tab(tabButton);
            tab.show();
        }
    }
    
    // ===== ADD LESSON MODAL FUNCTIONALITY =====
    
    // Modal step management
    let currentStep = 1;
    const totalSteps = 4;
    
    const nextStepBtn = document.getElementById('nextStepBtn');
    const prevStepBtn = document.getElementById('prevStepBtn');
    const submitLessonBtn = document.getElementById('submitLessonBtn');
    
    // Initialize modal
    const addLessonModal = document.getElementById('addLessonModal');
    addLessonModal.addEventListener('shown.bs.modal', function() {
        resetModal();
        showStep(1);
    });
    
    // Step navigation
    nextStepBtn.addEventListener('click', function() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        }
    });
    
    prevStepBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });
    
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-pane').forEach(pane => {
            pane.classList.remove('active');
        });
        
        // Hide all step indicators
        document.querySelectorAll('.step-indicator').forEach(indicator => {
            indicator.classList.remove('active', 'completed');
        });
        
        // Show current step
        document.getElementById(`step-${step}`).classList.add('active');
        
        // Update step indicators
        for (let i = 1; i <= step; i++) {
            const indicator = document.querySelector(`[data-step="${i}"]`);
            if (i === step) {
                indicator.classList.add('active');
            } else if (i < step) {
                indicator.classList.add('completed');
            }
        }
        
        // Update navigation buttons
        prevStepBtn.style.display = step > 1 ? 'block' : 'none';
        nextStepBtn.style.display = step < totalSteps ? 'block' : 'none';
        submitLessonBtn.style.display = step === totalSteps ? 'block' : 'none';
        
        // Update review section if on final step
        if (step === totalSteps) {
            updateReviewSection();
        }
        
        currentStep = step;
    }
    
    function validateCurrentStep() {
        switch (currentStep) {
            case 1:
                const title = document.getElementById('lesson_title').value.trim();
                if (!title) {
                    showError('Please enter a lesson title');
                    document.getElementById('lesson_title').focus();
                    return false;
                }
                break;
            case 2:
                const hasVideo = document.getElementById('video_file').files.length > 0;
                const hasVideoUrl = document.getElementById('video_url').value.trim();
                if (!hasVideo && !hasVideoUrl) {
                    showError('Please upload a video or provide a video URL');
                    return false;
                }
                break;
        }
        return true;
    }
    
    function resetModal() {
        currentStep = 1;
        document.getElementById('addLessonForm').reset();
        clearVideoUpload();
        document.getElementById('includeAssignment').checked = false;
        document.getElementById('assignmentSection').classList.add('d-none');
        document.querySelector('.url-preview').classList.add('d-none');
    }
    
    // Video upload functionality
    const videoUploadZone = document.getElementById('videoUploadZone');
    const videoFileInput = document.getElementById('video_file');
    
    // Drag and drop functionality
    videoUploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        videoUploadZone.classList.add('drag-over');
    });
    
    videoUploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        videoUploadZone.classList.remove('drag-over');
    });
    
    videoUploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        videoUploadZone.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('video/')) {
            videoFileInput.files = files;
            handleVideoUpload(files[0]);
        }
    });
    
    videoUploadZone.addEventListener('click', function() {
        if (!videoUploadZone.querySelector('.video-preview').classList.contains('d-none')) return;
        videoFileInput.click();
    });
    
    videoFileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleVideoUpload(this.files[0]);
        }
    });
    
    function handleVideoUpload(file) {
        // Validate file size (500MB)
        if (file.size > 500 * 1024 * 1024) {
            showError('File size exceeds 500MB limit');
            return;
        }
        
        // Show video preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('videoFileName').textContent = file.name;
            document.getElementById('videoFileSize').textContent = formatFileSize(file.size);
            document.getElementById('videoPreviewSource').src = e.target.result;
            
            const video = document.querySelector('.video-preview video');
            video.load();
            
            video.addEventListener('loadedmetadata', function() {
                document.getElementById('videoDuration').textContent = formatDuration(video.duration);
            });
            
            videoUploadZone.querySelector('.upload-placeholder').classList.add('d-none');
            videoUploadZone.querySelector('.video-preview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
    
    window.clearVideoUpload = function() {
        videoFileInput.value = '';
        videoUploadZone.querySelector('.upload-placeholder').classList.remove('d-none');
        videoUploadZone.querySelector('.video-preview').classList.add('d-none');
    };
    
    // Video URL functionality
    document.getElementById('video_url').addEventListener('input', function() {
        const url = this.value.trim();
        if (url && isValidVideoUrl(url)) {
            showVideoUrlPreview(url);
        } else {
            document.querySelector('.url-preview').classList.add('d-none');
        }
    });
    
    function isValidVideoUrl(url) {
        const patterns = [
            /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)/,
            /(?:https?:\/\/)?(?:www\.)?vimeo\.com/,
            /\.(mp4|avi|mov|wmv|flv)$/i
        ];
        return patterns.some(pattern => pattern.test(url));
    }
    
    function showVideoUrlPreview(url) {
        const previewContainer = document.getElementById('urlVideoPreview');
        
        if (url.includes('youtube.com') || url.includes('youtu.be')) {
            const videoId = extractYouTubeVideoId(url);
            if (videoId) {
                previewContainer.innerHTML = `
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/${videoId}" allowfullscreen></iframe>
                    </div>
                `;
                document.querySelector('.url-preview').classList.remove('d-none');
            }
        } else if (url.includes('vimeo.com')) {
            const videoId = url.split('/').pop();
            previewContainer.innerHTML = `
                <div class="ratio ratio-16x9">
                    <iframe src="https://player.vimeo.com/video/${videoId}" allowfullscreen></iframe>
                </div>
            `;
            document.querySelector('.url-preview').classList.remove('d-none');
        }
    }
    
    function extractYouTubeVideoId(url) {
        const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
        const match = url.match(regex);
        return match ? match[1] : null;
    }
    
    // Assignment toggle
    document.getElementById('includeAssignment').addEventListener('change', function() {
        const assignmentSection = document.getElementById('assignmentSection');
        if (this.checked) {
            assignmentSection.classList.remove('d-none');
        } else {
            assignmentSection.classList.add('d-none');
        }
    });
    
    // Update review section
    function updateReviewSection() {
        // Basic info
        document.getElementById('review-title').textContent = 
            document.getElementById('lesson_title').value || '-';
        document.getElementById('review-duration').textContent = 
            document.getElementById('lesson_duration').value || '-';
        document.getElementById('review-description').textContent = 
            document.getElementById('lesson_description').value || '-';
        
        // Video info
        const videoFile = document.getElementById('video_file').files[0];
        const videoUrl = document.getElementById('video_url').value;
        let videoReview = 'No video selected';
        
        if (videoFile) {
            videoReview = `<strong>Uploaded File:</strong> ${videoFile.name} (${formatFileSize(videoFile.size)})`;
        } else if (videoUrl) {
            videoReview = `<strong>Video URL:</strong> ${videoUrl}`;
        }
        
        document.getElementById('review-video').innerHTML = videoReview;
        
        // Assignment info
        const includeAssignment = document.getElementById('includeAssignment').checked;
        const assignmentCard = document.getElementById('review-assignment-card');
        
        if (includeAssignment) {
            const title = document.getElementById('assignment_title').value;
            const description = document.getElementById('assignment_description').value;
            const dueDate = document.getElementById('assignment_due_date').value;
            const maxPoints = document.getElementById('assignment_max_points').value;
            
            document.getElementById('review-assignment').innerHTML = `
                <strong>Title:</strong> ${title || 'Untitled Assignment'}<br>
                <strong>Instructions:</strong> ${description || 'No instructions provided'}<br>
                <strong>Due Date:</strong> ${dueDate ? new Date(dueDate).toLocaleString() : 'No due date'}<br>
                <strong>Points:</strong> ${maxPoints || '100'}
            `;
            assignmentCard.style.display = 'block';
        } else {
            assignmentCard.style.display = 'none';
        }
    }
    
    // Form submission
    document.getElementById('addLessonForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Add assignment data if enabled
        const includeAssignment = document.getElementById('includeAssignment').checked;
        formData.append('include_assignment', includeAssignment);
        
        // Show loading overlay
        const overlay = document.createElement('div');
        overlay.id = 'uploadOverlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        `;
        
        const progressBox = document.createElement('div');
        progressBox.style.cssText = `
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            min-width: 400px;
        `;
        
        // Check if video file is large
        const videoFile = document.getElementById('video_file').files[0];
        let message = 'Creating lesson...';
        let timeInfo = '';
        
        if (videoFile && videoFile.size > 50 * 1024 * 1024) {
            const fileSize = (videoFile.size / (1024 * 1024)).toFixed(2);
            const estimatedTime = Math.ceil(videoFile.size / (1024 * 1024) / 2);
            message = 'Uploading Video...';
            timeInfo = `
                <p class="text-muted mb-2">File Size: ${fileSize} MB</p>
                <p class="text-muted mb-3">Estimated Time: ${estimatedTime} seconds</p>
            `;
        }
        
        progressBox.innerHTML = `
            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h4 class="mb-3">${message}</h4>
            ${timeInfo}
            <div class="progress" style="height: 25px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" 
                     style="width: 100%">
                    Please wait...
                </div>
            </div>
            <p class="text-muted mt-3 small">
                <i class="fas fa-info-circle me-1"></i>
                Do not close this window or navigate away.
            </p>
        `;
        
        overlay.appendChild(progressBox);
        document.body.appendChild(overlay);
        
        // Disable submit button
        submitLessonBtn.disabled = true;
        submitLessonBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creating...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            overlay.remove();
            if (data.success) {
                showSuccess('Lesson created successfully!');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showError(data.message || 'Failed to create lesson');
                submitLessonBtn.disabled = false;
                submitLessonBtn.innerHTML = '<i class="fas fa-check me-1"></i>Create';
            }
        })
        .catch(error => {
            overlay.remove();
            console.error('Error:', error);
            showError('An error occurred while creating the lesson');
            submitLessonBtn.disabled = false;
            submitLessonBtn.innerHTML = '<i class="fas fa-check me-1"></i>Create';
        });
    });
    
    // Utility functions
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = Math.floor(seconds % 60);
        
        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        } else {
            return `${minutes}:${secs.toString().padStart(2, '0')}`;
        }
    }
    
    function showError(message) {
        // Create or update error alert
        let alertContainer = document.querySelector('.alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'alert-container position-fixed top-0 end-0 p-3';
            alertContainer.style.zIndex = '9999';
            document.body.appendChild(alertContainer);
        }
        
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        alertContainer.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
    
    function showSuccess(message) {
        let alertContainer = document.querySelector('.alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'alert-container position-fixed top-0 end-0 p-3';
            alertContainer.style.zIndex = '9999';
            document.body.appendChild(alertContainer);
        }
        
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        alertContainer.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }
    
    // Smooth scrolling for anchors
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

<style>
/* Custom styles for the course show page */
.lesson-order-badge {
    font-weight: 600;
    font-size: 14px;
}

.tab-content {
    min-height: 400px;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    background-color: transparent;
    border-bottom-color: #2c3e50;
    color: #2c3e50;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #dee2e6;
    color: #495057;
}

.card {
    transition: all 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}

.list-group-item {
    border: 1px solid #dee2e6;
    margin-bottom: 0.5rem;
    border-radius: 0.375rem;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.avatar img, .avatar div {
    transition: all 0.2s ease-in-out;
}

.avatar:hover img, .avatar:hover div {
    transform: scale(1.05);
}

/* ===== YOUTUBE-STYLE MODAL STYLES ===== */

/* Custom primary color to match sidebar */
.text-primary, .text-primary i {
    color: #2c3e50 !important;
}

/* Modal styling */
.modal-lg {
    max-width: 900px;
}

.modal-lg .modal-content {
    border-radius: 8px;
}

.modal-lg .modal-body {
    padding: 1rem;
    overflow: hidden;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
}

.modal-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Progress steps styling */
.progress-container {
    position: sticky;
    top: 0;
    z-index: 1000;
}

.step-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
}

.step-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 14px;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

.step-indicator.active .step-circle {
    background: #2c3e50;
    color: white;
    border-color: #2c3e50;
    transform: scale(1.1);
}

.step-indicator.completed .step-circle {
    background: #198754;
    color: white;
    border-color: #198754;
}

.step-label {
    margin-top: 0.5rem;
    font-size: 12px;
    font-weight: 500;
    color: #6c757d;
    text-align: center;
}

.step-indicator.active .step-label {
    color: #2c3e50;
    font-weight: 600;
}

.step-indicator.completed .step-label {
    color: #198754;
    font-weight: 600;
}

.step-line {
    height: 2px;
    background: #e9ecef;
    flex: 1;
    margin: 0 1rem;
    position: relative;
    top: -20px;
}

.step-indicator.completed + .step-line {
    background: #198754;
}

/* Step content styling */
.step-content {
    height: auto;
    overflow: hidden;
}

.step-pane {
    display: none;
    animation: fadeIn 0.3s ease-in-out;
    padding: 0.5rem 0;
}

.step-pane.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Video upload zone styling */
.video-upload-zone {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    background: #f8f9fa;
    position: relative;
}

.video-upload-zone.compact {
    padding: 1.5rem;
}

.video-upload-zone:hover {
    border-color: #2c3e50;
    background: #f8f9fa;
}

.video-upload-zone.drag-over {
    border-color: #2c3e50;
    background: #ecf0f1;
    transform: scale(1.02);
}

.upload-icon {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.video-preview {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #dee2e6;
}

.upload-progress {
    margin-top: 1rem;
}

.progress {
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    background: linear-gradient(90deg, #2c3e50 0%, #34495e 100%);
    transition: width 0.3s ease;
}

/* URL preview styling */
.url-preview {
    margin-top: 1rem;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Assignment section styling */
#assignmentSection {
    animation: slideDown 0.3s ease;
}

/* Form controls styling */
.form-control:focus {
    border-color: #2c3e50;
    box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
}

.form-control-lg {
    font-size: 1.1rem;
    padding: 0.75rem 1rem;
}

/* Review section styling */
.review-section .card {
    border-left: 4px solid #2c3e50;
}

.review-section .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
}

/* Button styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    border: none;
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1a252f 0%, #2c3e50 100%);
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1c9e7e 100%);
}

/* Modal footer styling */
.modal-footer {
    padding: 0.5rem 1rem !important;
    border-top: 1px solid #e3e6ea;
    background: #f8f9fa;
    min-height: auto;
}

.modal-footer .btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    line-height: 1.2;
}

/* Form validation styling */
.form-control.is-invalid {
    border-color: #dc3545;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
    position: relative;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #2c3e50;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    z-index: 1000;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Alert container */
.alert-container {
    max-width: 400px;
}

.alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-lg {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
    }
    
    .modal-content {
        border-radius: 8px;
        height: 95vh !important;
        max-height: none !important;
    }
    
    .modal-dialog-scrollable .modal-body {
        flex: 1;
        padding: 0;
    }
    
    .step-indicator {
        margin-bottom: 0.5rem;
    }
    
    .step-line {
        display: none;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 0.25rem;
    }
    
    .nav-tabs {
        flex-wrap: wrap;
    }
    
    .nav-tabs .nav-item {
        margin-bottom: 0.5rem;
    }
    
    .video-upload-zone {
        padding: 1rem;
    }
    
    .video-upload-zone.compact {
        padding: 0.75rem;
    }
}

/* Custom scrollbar for modal */
.modal-body {
    scrollbar-width: thin;
    scrollbar-color: #2c3e50 #f1f1f1;
}

.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #2c3e50;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #1a252f;
}

/* Pill navigation styling */
.nav-pills .nav-link {
    border-radius: 20px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    box-shadow: 0 2px 8px rgba(44, 62, 80, 0.3);
}

/* Video iframe responsive */
.ratio {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>
@endpush

@php
    $confirmMessage = $course->status === 'published' ? 'Unpublish this course?' : 'Publish this course?';
    $toggleRouteUrl = route('teacher.courses.toggle-status', $course);
@endphp

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusForm = document.querySelector('form[action="{{ $toggleRouteUrl }}"]');
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            if (!confirm('{{ $confirmMessage }}')) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush
