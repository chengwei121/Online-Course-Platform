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
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Upload Limit: 500MB</strong> - You can now upload large MP4 videos directly!
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="video_source" id="upload_video" value="upload" checked>
                                <label class="form-check-label" for="upload_video">
                                    <i class="fas fa-upload me-1"></i>Upload Video File (Max 500MB)
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
                                    <i class="fas fa-info-circle me-1"></i>Supported formats: MP4, AVI, MOV, WMV, FLV. 
                                    <strong>Maximum size: 500MB</strong><br>
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Large files may take several minutes to upload. 
                                        Please ensure stable internet connection.
                                    </small>
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
                                    <strong>Supported platforms:</strong> YouTube, Vimeo, and direct video URLs<br>
                                    <small class="text-success">✅ This option bypasses the 10MB upload limit</small>
                                </div>
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="alert alert-info">
                                <h6><i class="fas fa-lightbulb me-2"></i>Quick Video Hosting Options:</h6>
                                <ul class="mb-0">
                                    <li><strong>YouTube:</strong> Upload to YouTube and use the share URL</li>
                                    <li><strong>Google Drive:</strong> Upload and get shareable link</li>
                                    <li><strong>Dropbox:</strong> Upload and create public link</li>
                                    <li><strong>Vimeo:</strong> Free hosting with direct URLs</li>
                                </ul>
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

    // Initialize with upload section visible (since it's checked by default)
    urlSection.style.display = 'none';
    uploadSection.style.display = 'block';

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

    // Handle video file preview with strict size checking
    videoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Display file information immediately
            const fileSizeInfo = document.getElementById('file-size-info');
            if (fileSizeInfo) {
                fileSizeInfo.remove();
            }
            
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            const maxSizeMB = 500; // Updated PHP limit
            
            let sizeClass = 'text-success';
            let sizeMessage = `✅ File size: ${fileSizeMB}MB (OK)`;
            
            if (file.size > maxSizeMB * 1024 * 1024) {
                sizeClass = 'text-danger';
                sizeMessage = `❌ File size: ${fileSizeMB}MB (TOO LARGE!)`;
                
                // Show prominent error and force URL option
                const errorHtml = `<div class="alert alert-danger mt-2" id="file-size-info">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>File Too Large!</h6>
                    <p><strong>${sizeMessage}</strong></p>
                    <p>Current limit: ${maxSizeMB}MB</p>
                    <hr>
                    <p class="mb-0"><strong>Solution:</strong> Use the "Video URL" option instead. Upload your video to YouTube, Google Drive, or Dropbox first.</p>
                    <button type="button" class="btn btn-primary btn-sm mt-2" id="switch-to-url">
                        <i class="fas fa-link me-1"></i>Switch to Video URL Option
                    </button>
                </div>`;
                
                this.parentNode.parentNode.insertAdjacentHTML('afterend', errorHtml);
                
                // Add click handler to switch to URL option
                document.getElementById('switch-to-url').addEventListener('click', function() {
                    urlRadio.click();
                    document.getElementById('video_url').focus();
                });
                
                // Clear the file input
                this.value = '';
                hideVideoPreview();
                return;
            } else if (file.size > 5 * 1024 * 1024) {
                sizeClass = 'text-warning';
                sizeMessage = `⚠️ File size: ${fileSizeMB}MB (Large file)`;
            }
            
            const sizeText = `<div class="alert alert-info mt-2" id="file-size-info">
                <strong>Video Information:</strong><br>
                <span class="${sizeClass}"><strong>${sizeMessage}</strong></span><br>
                <small>File name: ${file.name}</small><br>
                <small>File type: ${file.type}</small>
            </div>`;
            
            this.parentNode.parentNode.insertAdjacentHTML('afterend', sizeText);

            // Only proceed with preview if file size is acceptable
            const url = URL.createObjectURL(file);
            const video = videoPreview.querySelector('video');
            video.src = url;
            videoPreviewBtn.style.display = 'inline-block';
        } else {
            hideVideoPreview();
            const fileSizeInfo = document.getElementById('file-size-info');
            if (fileSizeInfo) {
                fileSizeInfo.remove();
            }
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

    // Form submission with enhanced upload handling and size checking
    form.addEventListener('submit', function(e) {
        const videoFile = videoInput.files[0];
        const isUploadMode = uploadRadio.checked;
        
        // Prevent submission if upload mode is selected and file is too large
        if (isUploadMode && videoFile) {
            const maxSizeMB = 500;
            if (videoFile.size > maxSizeMB * 1024 * 1024) {
                e.preventDefault();
                alert(`Cannot submit! Your video file (${(videoFile.size / (1024 * 1024)).toFixed(2)}MB) exceeds the ${maxSizeMB}MB limit.\n\nPlease use a smaller file or contact support.`);
                return false;
            }
        }
        
        // Prevent default submission for AJAX handling if video file is large but within limits
        if (videoFile && videoFile.size > 100 * 1024 * 1024) { // 100MB+
            e.preventDefault();
            handleLargeFileUpload();
            return;
        }
        
        // Regular form submission for smaller files or URL mode
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Lesson...';
    });

    function handleLargeFileUpload() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
        
        showUploadProgress();
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            // Handle non-JSON responses (like 413 errors that return HTML)
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                if (response.status === 413) {
                    throw new Error('File too large for upload. Please use the Video URL option instead.');
                }
                throw new Error(`Upload failed: Server returned ${response.status} ${response.statusText}`);
            }
            
            if (!response.ok) {
                throw new Error(`Upload failed: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                hideUploadProgress();
                showSuccess('Lesson created successfully! Redirecting...');
                setTimeout(() => {
                    window.location.href = data.redirect || '{{ route("teacher.courses.lessons.index", $course) }}';
                }, 1000);
            } else {
                throw new Error(data.message || 'Upload failed');
            }
        })
        .catch(error => {
            hideUploadProgress();
            console.error('Upload error:', error);
            
            // Show specific error message with solution
            let errorMessage = error.message;
            if (errorMessage.includes('too large') || errorMessage.includes('413') || errorMessage.includes('Content Too Large')) {
                errorMessage = 'File too large for upload! Please use the "Video URL" option instead.';
                
                // Automatically switch to URL option
                setTimeout(() => {
                    urlRadio.click();
                    document.getElementById('video_url').focus();
                }, 2000);
            }
            
            showError(errorMessage);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Create Lesson';
        });
    }

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

    function hideUploadProgress() {
        const progressDiv = document.getElementById('upload-progress');
        if (progressDiv) {
            progressDiv.remove();
        }
    }

    function showError(message) {
        const errorHtml = `
            <div class="alert alert-danger mt-3" id="upload-error">
                <strong>Upload Error:</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        form.insertAdjacentHTML('beforeend', errorHtml);
        
        // Remove error after 10 seconds
        setTimeout(() => {
            const errorDiv = document.getElementById('upload-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }, 10000);
    }

    function showSuccess(message) {
        const successHtml = `
            <div class="alert alert-success mt-3" id="upload-success">
                <strong>Success!</strong> ${message}
            </div>
        `;
        form.insertAdjacentHTML('beforeend', successHtml);
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