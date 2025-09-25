@extends('layouts.admin')

@section('title', 'Create New Course')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
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
                            <li class="breadcrumb-item active text-white" aria-current="page">Create New Course</li>
                        </ol>
                    </nav>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="display-6 fw-bold mb-2">
                                <i class="fas fa-plus-circle me-3"></i>Create New Course
                            </h1>
                            <p class="mb-0 opacity-75">Build engaging learning experiences for your students</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i>Back to Courses
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" id="courseForm">
        @csrf
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
                                       value="{{ old('title') }}" 
                                       required 
                                       placeholder="Enter an engaging course title">
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
                                    <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
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
                                      placeholder="Describe what students will learn in this course">{{ old('description') }}</textarea>
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
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="teacher_id" class="form-label">Instructor <span class="text-danger">*</span></label>
                                <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                        id="teacher_id" 
                                        name="teacher_id" 
                                        required>
                                    <option value="">Select an instructor</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('teacher_id') == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
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
                                <label for="price" class="form-label">Price (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price') }}" 
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
                                       value="{{ old('duration') }}" 
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
                                       value="{{ old('learning_hours') }}" 
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
                                      placeholder="Enter skills separated by commas (e.g., HTML, CSS, JavaScript, React)">{{ old('skills_to_learn') }}</textarea>
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
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
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
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Create Course
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Course Thumbnail -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image me-2"></i>Course Thumbnail
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Upload Thumbnail</label>
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
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                    <i class="fas fa-times me-1"></i>Remove
                                </button>
                            </div>
                        </div>

                        <!-- Default Preview -->
                        <div id="defaultPreview" class="text-center">
                            <div class="border border-2 border-dashed rounded d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                <div class="text-muted">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                    <div>Click to upload thumbnail</div>
                                    <small>Recommended: 800x600px</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Tips -->
                <div class="card bg-success text-white mb-4">
                    <div class="card-header border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-lightbulb me-2"></i>Course Creation Tips
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item bg-transparent border-0 text-white px-0">
                                <i class="fas fa-check me-2"></i>
                                <small>Use a clear and descriptive title</small>
                            </div>
                            <div class="list-group-item bg-transparent border-0 text-white px-0">
                                <i class="fas fa-check me-2"></i>
                                <small>Include relevant keywords in description</small>
                            </div>
                            <div class="list-group-item bg-transparent border-0 text-white px-0">
                                <i class="fas fa-check me-2"></i>
                                <small>Choose appropriate difficulty level</small>
                            </div>
                            <div class="list-group-item bg-transparent border-0 text-white px-0">
                                <i class="fas fa-check me-2"></i>
                                <small>Upload high-quality thumbnail</small>
                            </div>
                            <div class="list-group-item bg-transparent border-0 text-white px-0">
                                <i class="fas fa-check me-2"></i>
                                <small>List specific skills students will learn</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Status Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Status Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex align-items-center px-0 border-0">
                                <span class="badge bg-secondary me-2">Draft</span>
                                <small class="text-muted">Course is not visible to students</small>
                            </div>
                            <div class="list-group-item d-flex align-items-center px-0 border-0">
                                <span class="badge bg-success me-2">Published</span>
                                <small class="text-muted">Course is live and available</small>
                            </div>
                            <div class="list-group-item d-flex align-items-center px-0 border-0">
                                <span class="badge bg-warning me-2">Archived</span>
                                <small class="text-muted">Course is hidden but preserved</small>
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
    const defaultPreview = document.getElementById('defaultPreview');
    const previewImg = document.getElementById('previewImg');

    thumbnailInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('d-none');
                defaultPreview.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    const form = document.getElementById('courseForm');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = ['title', 'description', 'category_id', 'teacher_id', 'level'];
        
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
    document.getElementById('defaultPreview').classList.remove('d-none');
}
</script>
@endpush
@endsection
