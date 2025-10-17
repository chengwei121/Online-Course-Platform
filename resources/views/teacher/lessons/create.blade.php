@extends('teacher.layouts.app')

@section('title', 'Create New Lesson')
@section('page-title', 'Create Lesson')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}">{{ Str::limit($course->title, 30) }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.lessons.index', $course) }}">Lessons</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Create New Lesson
                </h1>
                <a href="{{ route('teacher.courses.lessons.index', $course) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Lessons
                </a>
            </div>

            <!-- Create Form -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('teacher.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data" id="createLessonForm">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-1"></i>Lesson Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   placeholder="e.g., Introduction to HTML"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Brief description of what this lesson covers..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Video Options -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-video me-1"></i>Video
                            </label>
                            
                            <!-- Video Upload/URL Tabs -->
                            <ul class="nav nav-tabs mb-3" id="videoTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-panel" type="button">
                                        <i class="fas fa-upload me-1"></i>Upload Video
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="url-tab" data-bs-toggle="tab" data-bs-target="#url-panel" type="button">
                                        <i class="fas fa-link me-1"></i>Video URL
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="videoTabContent">
                                <!-- Upload Video Tab -->
                                <div class="tab-pane fade show active" id="upload-panel">
                                    <input type="file" 
                                           class="form-control @error('video') is-invalid @enderror" 
                                           id="video" 
                                           name="video" 
                                           accept="video/*"
                                           onchange="displayVideoFileName(this)">
                                    <small class="form-text text-muted">
                                        Supported formats: MP4, AVI, MOV, WMV, FLV (Max: 500MB)
                                    </small>
                                    <div id="videoFileName" class="mt-2"></div>
                                    @error('video')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Video URL Tab -->
                                <div class="tab-pane fade" id="url-panel">
                                    <input type="url" 
                                           class="form-control @error('video_url') is-invalid @enderror" 
                                           id="video_url" 
                                           name="video_url" 
                                           value="{{ old('video_url') }}" 
                                           placeholder="https://youtube.com/watch?v=... or https://vimeo.com/...">
                                    <small class="form-text text-muted">
                                        Enter YouTube, Vimeo, or any other video URL
                                    </small>
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Duration -->
                            <div class="col-md-6 mb-4">
                                <label for="duration" class="form-label">
                                    <i class="fas fa-clock me-1"></i>Duration (minutes)
                                </label>
                                <input type="number" 
                                       class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" 
                                       name="duration" 
                                       value="{{ old('duration') }}" 
                                       min="1" 
                                       placeholder="e.g., 15">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Order -->
                            <div class="col-md-6 mb-4">
                                <label for="order" class="form-label">
                                    <i class="fas fa-sort-numeric-down me-1"></i>Lesson Order <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('order') is-invalid @enderror" 
                                       id="order" 
                                       name="order" 
                                       value="{{ old('order', $nextOrder) }}" 
                                       min="1" 
                                       required>
                                <small class="form-text text-muted">
                                    Current suggested order: {{ $nextOrder }}
                                </small>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label for="content" class="form-label">
                                <i class="fas fa-file-alt me-1"></i>Lesson Content
                            </label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="6"
                                      placeholder="Detailed lesson content, notes, or transcript...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Learning Objectives -->
                        <div class="mb-4">
                            <label for="learning_objectives" class="form-label">
                                <i class="fas fa-bullseye me-1"></i>Learning Objectives
                            </label>
                            <textarea class="form-control @error('learning_objectives') is-invalid @enderror" 
                                      id="learning_objectives" 
                                      name="learning_objectives" 
                                      rows="4"
                                      placeholder="What students will learn from this lesson...">{{ old('learning_objectives') }}</textarea>
                            @error('learning_objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('teacher.courses.lessons.index', $course) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i>Create Lesson
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function displayVideoFileName(input) {
        const fileNameDiv = document.getElementById('videoFileName');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileSize = (file.size / (1024 * 1024)).toFixed(2); // Convert to MB
            
            if (fileSize > 500) {
                fileNameDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>File too large!</strong> The file size is ${fileSize} MB. Maximum allowed is 500 MB.
                    </div>
                `;
                input.value = '';
            } else {
                fileNameDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-file-video me-2"></i>
                        <strong>Selected:</strong> ${file.name} (${fileSize} MB)
                    </div>
                `;
            }
        } else {
            fileNameDiv.innerHTML = '';
        }
    }

    // Disable video upload when URL is being entered and vice versa
    document.addEventListener('DOMContentLoaded', function() {
        const videoFileInput = document.getElementById('video');
        const videoUrlInput = document.getElementById('video_url');
        const form = document.getElementById('createLessonForm');
        const submitBtn = document.getElementById('submitBtn');
        
        videoFileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                videoUrlInput.value = '';
            }
        });
        
        videoUrlInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                videoFileInput.value = '';
                document.getElementById('videoFileName').innerHTML = '';
            }
        });

        // Show loading state on form submit (especially for large video uploads)
        form.addEventListener('submit', function(e) {
            if (videoFileInput.files.length > 0) {
                const file = videoFileInput.files[0];
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                
                if (fileSize > 100) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Uploading... This may take a while';
                    submitBtn.disabled = true;
                    
                    // Show progress message
                    const progressDiv = document.createElement('div');
                    progressDiv.className = 'alert alert-info mt-3';
                    progressDiv.innerHTML = `
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Uploading large video file (${fileSize} MB)...</strong> 
                        Please do not close this page. This may take several minutes.
                    `;
                    form.appendChild(progressDiv);
                }
            }
        });
    });
</script>
@endpush
@endsection
