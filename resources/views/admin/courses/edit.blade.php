@extends('layouts.admin')

@section('title', 'Edit Course - ' . $course->title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb text-white-50">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" class="text-white-50 text-decoration-none">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.courses.index') }}" class="text-white-50 text-decoration-none">Courses</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.courses.show', $course) }}" class="text-white-50 text-decoration-none">{{ Str::limit($course->title, 30) }}</a>
                            </li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Edit</li>
                        </ol>
                    </nav>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="display-6 fw-bold mb-2">
                                <i class="fas fa-edit me-3"></i>Edit Course
                            </h1>
                            <p class="mb-0 opacity-75">Update course information and settings</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-light me-2">
                                <i class="fas fa-eye me-2"></i>View Course
                            </a>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Course Title <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $course->title) }}" 
                                       required 
                                       placeholder="Enter course title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="level" class="form-label">Level <span class="text-danger">*</span></label>
                                <select class="form-select @error('level') is-invalid @enderror" 
                                        id="level" 
                                        name="level" 
                                        required>
                                    <option value="">Select Level</option>
                                    <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required 
                                      placeholder="Provide a detailed description of the course">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" 
                                        name="category_id" 
                                        required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="instructor_id" class="form-label">Instructor <span class="text-danger">*</span></label>
                                <select class="form-select @error('instructor_id') is-invalid @enderror" 
                                        id="instructor_id" 
                                        name="instructor_id" 
                                        required>
                                    <option value="">Select Instructor</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cog me-2"></i>Course Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Price (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', $course->price ?? 0) }}" 
                                           min="0" 
                                           step="0.01" 
                                           placeholder="0.00">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Set to 0 for free courses</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="duration" class="form-label">Course Duration</label>
                                <input type="text" 
                                       class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" 
                                       name="duration" 
                                       value="{{ old('duration', $course->duration) }}" 
                                       placeholder="e.g., 8 weeks, 3 months">
                                <div class="form-text">Estimated time to complete</div>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="learning_hours" class="form-label">Learning Hours</label>
                                <input type="number" 
                                       class="form-control @error('learning_hours') is-invalid @enderror" 
                                       id="learning_hours" 
                                       name="learning_hours" 
                                       value="{{ old('learning_hours', $course->learning_hours) }}" 
                                       min="1" 
                                       placeholder="Total hours">
                                <div class="form-text">Total hours of content</div>
                                @error('learning_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="skills_to_learn" class="form-label">Skills Students Will Learn</label>
                            <textarea class="form-control @error('skills_to_learn') is-invalid @enderror" 
                                      id="skills_to_learn" 
                                      name="skills_to_learn" 
                                      rows="3" 
                                      placeholder="Enter skills separated by commas">{{ old('skills_to_learn', is_array($course->skills_to_learn) ? implode(', ', $course->skills_to_learn) : $course->skills_to_learn) }}</textarea>
                            <div class="form-text">Separate skills with commas</div>
                            @error('skills_to_learn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Course Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status">
                                <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $course->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Course
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Current Thumbnail -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image me-2"></i>Course Thumbnail
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Current Image -->
                        @if($course->thumbnail)
                            <div id="currentThumbnail" class="mb-3">
                                <div class="text-center">
                                    <img src="{{ $course->thumbnail }}" 
                                         alt="Current thumbnail" 
                                         class="img-fluid rounded border" 
                                         style="max-height: 200px;">
                                </div>
                                <div class="mt-2 text-center">
                                    <small class="text-muted">Current thumbnail</small>
                                </div>
                            </div>
                        @endif

                        <!-- Upload New -->
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">
                                {{ $course->thumbnail ? 'Replace Thumbnail' : 'Upload Thumbnail' }}
                            </label>
                            <input type="file" 
                                   class="form-control @error('thumbnail') is-invalid @enderror" 
                                   id="thumbnail" 
                                   name="thumbnail" 
                                   accept="image/*">
                            <div class="form-text">JPEG, PNG, JPG, GIF (Max: 2MB)</div>
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Preview -->
                        <div id="imagePreview" class="text-center d-none">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid rounded border" style="max-height: 200px;">
                            <div class="mt-2">
                                <small class="text-muted">New thumbnail preview</small>
                                <div class="mt-1">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                        <i class="fas fa-times me-1"></i>Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if(!$course->thumbnail)
                        <!-- Default Preview -->
                        <div id="defaultPreview" class="text-center">
                            <div class="border border-2 border-dashed rounded d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                <div class="text-muted">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                    <div>No thumbnail uploaded</div>
                                    <small>Upload to add course image</small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Course Statistics -->
                <div class="card bg-info text-white mb-4">
                    <div class="card-header border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Course Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end border-white">
                                    <h4 class="fw-bold">{{ $course->enrollments->count() }}</h4>
                                    <small>Students</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="fw-bold">{{ $course->lessons->count() }}</h4>
                                <small>Lessons</small>
                            </div>
                        </div>
                        <hr class="border-white">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end border-white">
                                    <h4 class="fw-bold">{{ $course->reviews->count() }}</h4>
                                    <small>Reviews</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="fw-bold">
                                    @if($course->average_rating > 0)
                                        {{ number_format($course->average_rating, 1) }}
                                    @else
                                        --
                                    @endif
                                </h4>
                                <small>Rating</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Course Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0 d-flex justify-content-between">
                                <span class="text-muted">Created:</span>
                                <span>{{ $course->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="list-group-item border-0 px-0 d-flex justify-content-between">
                                <span class="text-muted">Updated:</span>
                                <span>{{ $course->updated_at->format('M d, Y') }}</span>
                            </div>
                            <div class="list-group-item border-0 px-0 d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span>
                                    @if($course->status == 'published')
                                        <span class="badge bg-success">Published</span>
                                    @elseif($course->status == 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @else
                                        <span class="badge bg-secondary">Archived</span>
                                    @endif
                                </span>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="text-muted mb-1">Course ID:</div>
                                <code class="bg-light p-1 rounded">#{{ $course->id }}</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle image preview
    const thumbnailInput = document.getElementById('thumbnail');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const defaultPreview = document.getElementById('defaultPreview');

    thumbnailInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('d-none');
                if (defaultPreview) {
                    defaultPreview.classList.add('d-none');
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    const form = document.getElementById('courseForm');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = ['title', 'description', 'category_id', 'instructor_id', 'level'];
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            const feedback = field.parentElement.querySelector('.invalid-feedback');
            
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                if (feedback) {
                    feedback.textContent = 'This field is required.';
                }
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
});

function removeImage() {
    document.getElementById('thumbnail').value = '';
    document.getElementById('imagePreview').classList.add('d-none');
    const defaultPreview = document.getElementById('defaultPreview');
    if (defaultPreview) {
        defaultPreview.classList.remove('d-none');
    }
}
</script>
@endpush
@endsection
        width: 200px;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #e9ecef;
    }
    .upload-area {
        border: 2px dashed #667eea;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    .upload-area:hover {
        background: #e9ecef;
        border-color: #5a6fc8;
    }
    .section-title {
        color: #667eea;
        font-weight: bold;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    .required-asterisk {
        color: #dc3545;
    }
    .current-info {
        background: #e7f3ff;
        border: 1px solid #b3d7ff;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .skill-tag {
        background: #667eea;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        margin: 0.2rem;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-edit me-3"></i>
                    Edit Course
                </h1>
                <p class="mb-0 opacity-75">Update "{{ $course->title }}"</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}" class="text-white-50">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.show', $course) }}" class="text-white-50">{{ Str::limit($course->title, 20) }}</a></li>
                    <li class="breadcrumb-item active text-white">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="form-card">
            <h4 class="section-title">
                <i class="fas fa-info-circle me-2"></i>Basic Information
            </h4>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">
                            Course Title <span class="required-asterisk">*</span>
                        </label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $course->title) }}" 
                               placeholder="Enter an engaging course title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            Course Description <span class="required-asterisk">*</span>
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Describe what students will learn in this course">{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">
                                    Category <span class="required-asterisk">*</span>
                                </label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="instructor_id" class="form-label">
                                    Instructor <span class="required-asterisk">*</span>
                                </label>
                                <select class="form-select @error('instructor_id') is-invalid @enderror" 
                                        id="instructor_id" name="instructor_id">
                                    <option value="">Select an instructor</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" 
                                                {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Course Thumbnail</label>
                        
                        @if($course->thumbnail)
                        <div class="current-info">
                            <strong>Current Thumbnail:</strong><br>
                            <img src="{{ $course->thumbnail }}" alt="Current thumbnail" class="current-thumbnail mt-2">
                        </div>
                        @endif
                        
                        <div class="upload-area">
                            <div id="thumbnail-preview" class="mb-3" style="display: none;">
                                <img id="preview-image" src="" alt="Preview" class="current-thumbnail">
                            </div>
                            <div id="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Click to upload new thumbnail</p>
                                <small class="text-muted">JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                            </div>
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                                   id="thumbnail" name="thumbnail" accept="image/*" style="opacity: 0; position: absolute; width: 100%; height: 100%; cursor: pointer;">
                        </div>
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Details -->
        <div class="form-card">
            <h4 class="section-title">
                <i class="fas fa-cog me-2"></i>Course Details
            </h4>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="price" class="form-label">
                            Price (USD) <span class="required-asterisk">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $course->price) }}" 
                                   min="0" step="0.01" placeholder="0.00">
                        </div>
                        <small class="text-muted">Set to 0 for free courses</small>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="level" class="form-label">
                            Difficulty Level <span class="required-asterisk">*</span>
                        </label>
                        <select class="form-select @error('level') is-invalid @enderror" 
                                id="level" name="level">
                            <option value="">Select level</option>
                            <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        @error('level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status" class="form-label">
                            Course Status <span class="required-asterisk">*</span>
                        </label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status">
                            <option value="">Select status</option>
                            <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $course->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="duration" class="form-label">Course Duration</label>
                        <input type="text" class="form-control @error('duration') is-invalid @enderror" 
                               id="duration" name="duration" value="{{ old('duration', $course->duration) }}" 
                               placeholder="e.g., 8 weeks, 3 months">
                        <small class="text-muted">Estimated time to complete the course</small>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="learning_hours" class="form-label">Learning Hours</label>
                        <input type="number" class="form-control @error('learning_hours') is-invalid @enderror" 
                               id="learning_hours" name="learning_hours" value="{{ old('learning_hours', $course->learning_hours) }}" 
                               min="1" placeholder="Total hours of content">
                        <small class="text-muted">Total hours of video/content</small>
                        @error('learning_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="skills_to_learn" class="form-label">Skills Students Will Learn</label>
                @if($course->skills_to_learn)
                <div class="current-info">
                    <strong>Current Skills:</strong><br>
                    @foreach($course->skills_to_learn as $skill)
                        <span class="skill-tag">{{ $skill }}</span>
                    @endforeach
                </div>
                @endif
                <textarea class="form-control @error('skills_to_learn') is-invalid @enderror" 
                          id="skills_to_learn" name="skills_to_learn" rows="3" 
                          placeholder="Enter skills separated by commas (e.g., HTML, CSS, JavaScript, React)">{{ old('skills_to_learn', is_array($course->skills_to_learn) ? implode(', ', $course->skills_to_learn) : $course->skills_to_learn) }}</textarea>
                <small class="text-muted">Separate skills with commas</small>
                @error('skills_to_learn')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Course Statistics -->
        <div class="form-card">
            <h4 class="section-title">
                <i class="fas fa-chart-bar me-2"></i>Course Statistics
            </h4>
            
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="current-info">
                        <h5 class="text-primary">{{ $course->enrollments->count() }}</h5>
                        <small class="text-muted">Total Enrollments</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="current-info">
                        <h5 class="text-success">${{ number_format($course->enrollments->sum(function($enrollment) use ($course) { return $course->is_free ? 0 : $course->price; }), 2) }}</h5>
                        <small class="text-muted">Total Revenue</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="current-info">
                        <h5 class="text-info">{{ $course->lessons->count() }}</h5>
                        <small class="text-muted">Total Lessons</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="current-info">
                        <h5 class="text-warning">{{ $course->reviews->count() }}</h5>
                        <small class="text-muted">Total Reviews</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-card">
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Cancel
                </a>
                <div>
                    <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-eye me-2"></i>View Course
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Update Course
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Thumbnail preview
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('thumbnail-preview').style.display = 'block';
            document.getElementById('upload-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection
