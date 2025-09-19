@extends('teacher.layouts.app')

@section('title', 'Create New Lesson - ' . $course->title)
@section('page-title', 'Create New Lesson')

@section('content')
<div class="container-fluid">
    <!-- Course Context -->
    <div class="card mb-4">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}">{{ Str::limit($course->title, 30) }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.lessons.index', $course) }}">Lessons</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Lesson</li>
                </ol>
            </nav>
            <h1 class="h3 mb-1">Create New Lesson</h1>
            <p class="text-muted mb-0">Add a new lesson to <strong>{{ $course->title }}</strong></p>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <form action="{{ route('teacher.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data" id="lessonForm">
                @csrf
                
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Lesson Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" 
                                           placeholder="Enter a clear, descriptive lesson title" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Lesson Order <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           id="order" name="order" value="{{ old('order', $nextOrder) }}" 
                                           min="1" required>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Lesson Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Provide a brief overview of what students will learn in this lesson" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration (minutes)</label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                           id="duration" name="duration" value="{{ old('duration') }}" 
                                           min="1" placeholder="e.g., 15">
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Content -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-video me-2"></i>Video Content
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="video_source" id="upload_video" value="upload" checked>
                                <label class="form-check-label" for="upload_video">
                                    <i class="fas fa-upload me-1"></i>Upload Video File
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="video_source" id="video_url_option" value="url">
                                <label class="form-check-label" for="video_url_option">
                                    <i class="fas fa-link me-1"></i>Use External URL
                                </label>
                            </div>
                        </div>

                        <!-- Video Upload Section -->
                        <div id="video-upload-section">
                            <div class="mb-3">
                                <label for="video" class="form-label">Video File</label>
                                <div class="input-group">
                                    <input type="file" class="form-control @error('video') is-invalid @enderror" 
                                           id="video" name="video" accept="video/*">
                                    <button class="btn btn-outline-secondary" type="button" id="video-preview-btn" style="display: none;">
                                        <i class="fas fa-eye me-1"></i>Preview
                                    </button>
                                </div>
                                <div class="form-text">
                                    Supported formats: MP4, AVI, MOV, WMV, FLV. Maximum size: 500MB
                                </div>
                                @error('video')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Video Preview -->
                            <div id="video-preview" style="display: none;">
                                <video controls class="w-100" style="max-height: 300px;">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>

                        <!-- Video URL Section -->
                        <div id="video-url-section" style="display: none;">
                            <div class="mb-3">
                                <label for="video_url" class="form-label">Video URL</label>
                                <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                                       id="video_url" name="video_url" value="{{ old('video_url') }}" 
                                       placeholder="https://youtube.com/watch?v=... or https://vimeo.com/...">
                                <div class="form-text">
                                    Support for YouTube, Vimeo, and direct video URLs
                                </div>
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lesson Content -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-file-text me-2"></i>Lesson Content
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="content" class="form-label">Lesson Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="6" 
                                      placeholder="Write the main content, notes, or transcript for this lesson...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="learning_objectives" class="form-label">Learning Objectives</label>
                            <textarea class="form-control @error('learning_objectives') is-invalid @enderror" 
                                      id="learning_objectives" name="learning_objectives" rows="4" 
                                      placeholder="List what students will be able to do after completing this lesson...">{{ old('learning_objectives') }}</textarea>
                            @error('learning_objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Example: "After this lesson, students will be able to: 1) Create responsive layouts, 2) Use CSS Grid..."
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.courses.lessons.index', $course) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Create Lesson
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Tips Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Video Upload Tips
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Format:</strong> Use MP4 for best compatibility across all devices
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Resolution:</strong> 1080p (1920x1080) or 720p (1280x720) recommended
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Audio:</strong> Ensure clear audio with minimal background noise
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Size:</strong> Keep file size under 500MB for faster upload
                        </div>
                    </div>
                    <div class="d-flex">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Length:</strong> Aim for 5-20 minutes per lesson for better engagement
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Guidelines -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Content Guidelines
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Lesson Structure:</h6>
                        <ul class="small mb-0">
                            <li>Start with clear learning objectives</li>
                            <li>Present content in logical order</li>
                            <li>Include practical examples</li>
                            <li>End with a summary or call-to-action</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Engagement Tips:</h6>
                        <ul class="small mb-0">
                            <li>Use clear, conversational language</li>
                            <li>Break complex topics into steps</li>
                            <li>Include interactive elements</li>
                            <li>Encourage student participation</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-arrow-right me-2"></i>After Creating Lesson
                    </h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">Once you create this lesson, you can:</p>
                    <ul class="small">
                        <li>Add assignments and quizzes</li>
                        <li>Upload additional resources</li>
                        <li>Preview the lesson as a student</li>
                        <li>Reorder lessons within the course</li>
                        <li>Track student progress</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadRadio = document.getElementById('upload_video');
    const urlRadio = document.getElementById('video_url_option');
    const uploadSection = document.getElementById('video-upload-section');
    const urlSection = document.getElementById('video-url-section');
    const videoInput = document.getElementById('video');
    const videoPreview = document.getElementById('video-preview');
    const videoPreviewBtn = document.getElementById('video-preview-btn');
    const form = document.getElementById('lessonForm');
    const submitBtn = document.getElementById('submitBtn');

    // Handle video source selection
    uploadRadio.addEventListener('change', function() {
        if (this.checked) {
            uploadSection.style.display = 'block';
            urlSection.style.display = 'none';
            document.getElementById('video_url').value = '';
        }
    });

    urlRadio.addEventListener('change', function() {
        if (this.checked) {
            uploadSection.style.display = 'none';
            urlSection.style.display = 'block';
            videoInput.value = '';
            hideVideoPreview();
        }
    });

    // Handle video file preview
    videoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const url = URL.createObjectURL(file);
            const video = videoPreview.querySelector('video');
            video.src = url;
            videoPreviewBtn.style.display = 'inline-block';
        } else {
            hideVideoPreview();
        }
    });

    videoPreviewBtn.addEventListener('click', function() {
        if (videoPreview.style.display === 'none') {
            videoPreview.style.display = 'block';
            this.innerHTML = '<i class="fas fa-eye-slash me-1"></i>Hide Preview';
        } else {
            hideVideoPreview();
        }
    });

    function hideVideoPreview() {
        videoPreview.style.display = 'none';
        videoPreviewBtn.style.display = 'none';
        videoPreviewBtn.innerHTML = '<i class="fas fa-eye me-1"></i>Preview';
    }

    // Form submission with loading state
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Lesson...';
        
        // Show upload progress for large files
        const videoFile = videoInput.files[0];
        if (videoFile && videoFile.size > 50 * 1024 * 1024) { // 50MB
            showUploadProgress();
        }
    });

    function showUploadProgress() {
        const progressHtml = `
            <div class="alert alert-info mt-3" id="upload-progress">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    <div>
                        <strong>Uploading video...</strong><br>
                        <small>This may take a few minutes for large files. Please don't close this page.</small>
                    </div>
                </div>
            </div>
        `;
        form.insertAdjacentHTML('beforeend', progressHtml);
    }

    // Auto-generate lesson title suggestions based on course
    const titleInput = document.getElementById('title');
    titleInput.addEventListener('focus', function() {
        if (!this.value) {
            const orderNum = document.getElementById('order').value;
            this.placeholder = `Lesson ${orderNum}: Enter lesson title`;
        }
    });
});
</script>
@endpush