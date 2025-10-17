@extends('teacher.layouts.app')

@section('title', 'Edit Lesson - ' . $lesson->title)
@section('page-title', 'Edit Lesson')

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
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}">{{ Str::limit($lesson->title, 30) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Lesson
                </h1>
                <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Cancel
                </a>
            </div>

            <!-- Edit Form -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('teacher.courses.lessons.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data" id="editLessonForm">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-1"></i>Lesson Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $lesson->title) }}" 
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
                                      required>{{ old('description', $lesson->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Video Options -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-video me-1"></i>Video
                            </label>
                            
                            <!-- Current Video Display -->
                            @if($lesson->video_url)
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Current Video:</strong>
                                @if(filter_var($lesson->video_url, FILTER_VALIDATE_URL))
                                    <a href="{{ $lesson->video_url }}" target="_blank" class="alert-link">
                                        {{ Str::limit($lesson->video_url, 60) }}
                                    </a>
                                @else
                                    <span>{{ $lesson->video_url }}</span>
                                    <br>
                                    <video controls class="mt-2" style="max-width: 400px; max-height: 225px;">
                                        <source src="{{ $lesson->getDisplayVideoUrl() }}" type="video/mp4">
                                    </video>
                                @endif
                            </div>
                            @endif

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
                                           value="{{ old('video_url', filter_var($lesson->video_url, FILTER_VALIDATE_URL) ? $lesson->video_url : '') }}" 
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
                                       value="{{ old('duration', $lesson->duration) }}" 
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
                                       value="{{ old('order', $lesson->order) }}" 
                                       min="1" 
                                       required>
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
                                      placeholder="Detailed lesson content, notes, or transcript...">{{ old('content', $lesson->content) }}</textarea>
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
                                      placeholder="What students will learn from this lesson...">{{ old('learning_objectives', $lesson->learning_objectives) }}</textarea>
                            @error('learning_objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Lesson
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
            fileNameDiv.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-file-video me-2"></i>
                    <strong>Selected:</strong> ${file.name} (${fileSize} MB)
                </div>
            `;
        } else {
            fileNameDiv.innerHTML = '';
        }
    }

    // Disable video upload when URL is being entered and vice versa
    document.addEventListener('DOMContentLoaded', function() {
        const videoFileInput = document.getElementById('video');
        const videoUrlInput = document.getElementById('video_url');
        
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
    });
</script>
@endpush
@endsection
