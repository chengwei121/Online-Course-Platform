@extends('teacher.layouts.app')

@section('title', 'Create New Course')
@section('page-title', 'Create New Course')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Course Creation Form -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Create New Course
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('teacher.courses.store') }}" method="POST" enctype="multipart/form-data" id="courseForm">
                        @csrf
                        
                        <!-- Category Selection -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">
                                <i class="fas fa-folder me-1"></i>Category *
                            </label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Select a Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $selectedCategoryId ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Select a category to see existing courses and create your own
                            </div>
                        </div>

                        <!-- Course Details -->
                        <div id="courseDetails" style="{{ old('category_id', $selectedCategoryId ?? '') ? '' : 'display: none;' }}">
                            <hr class="my-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-book me-1"></i>Course Information
                            </h6>

                            <!-- Course Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Course Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Course Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price and Level Row -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price (RM) *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price', '0') }}" 
                                               min="0" step="0.01">
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text small">Enter 0 for free courses or check the free course option below</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="level" class="form-label">Difficulty Level *</label>
                                    <select class="form-select @error('level') is-invalid @enderror" 
                                            id="level" name="level" required>
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

                            <!-- Duration and Free Course Row -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="learning_hours" class="form-label">Learning Hours *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('learning_hours') is-invalid @enderror" 
                                               id="learning_hours" name="learning_hours" value="{{ old('learning_hours', '1') }}" 
                                               min="1" required>
                                        <span class="input-group-text">hours</span>
                                    </div>
                                    @error('learning_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Course Type</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_free" name="is_free" 
                                               value="1" {{ old('is_free') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_free">
                                            <i class="fas fa-gift me-1"></i>Make this course free
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Skills to Learn -->
                            <div class="mb-3">
                                <label for="skills_to_learn" class="form-label">Skills Students Will Learn</label>
                                <textarea class="form-control @error('skills_to_learn') is-invalid @enderror" 
                                          id="skills_to_learn" name="skills_to_learn" rows="3" 
                                          placeholder="Enter skills separated by commas (e.g., HTML, CSS, JavaScript)">{{ old('skills_to_learn') }}</textarea>
                                @error('skills_to_learn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Separate multiple skills with commas</div>
                            </div>

                            <!-- Course Thumbnail -->
                            <div class="mb-4">
                                <label for="thumbnail" class="form-label">Course Thumbnail</label>
                                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                                       id="thumbnail" name="thumbnail" accept="image/*">
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Recommended size: 800x450px. Max file size: 2MB
                                </div>
                                
                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-2" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('teacher.courses.index') }}" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Create Course
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Category Courses Display -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Courses in Category
                    </h5>
                </div>
                <div class="card-body">
                    <div id="categoryCoursesContainer">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                            <h6>Select a category to view existing courses</h6>
                            <p class="small">You'll be able to edit your own courses and view others for reference</p>
                        </div>
                    </div>
                    
                    <!-- Loading State -->
                    <div id="loadingCourses" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading courses...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const courseDetails = document.getElementById('courseDetails');
    const categoryCoursesContainer = document.getElementById('categoryCoursesContainer');
    const loadingCourses = document.getElementById('loadingCourses');
    const thumbnailInput = document.getElementById('thumbnail');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const isFreeCheckbox = document.getElementById('is_free');
    const priceInput = document.getElementById('price');

    // Category selection handler
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        console.log('Category changed to:', categoryId);
        
        if (categoryId) {
            courseDetails.style.display = 'block';
            loadCoursesByCategory(categoryId);
        } else {
            courseDetails.style.display = 'none';
            showDefaultMessage();
        }
    });

    // Load courses by category
    function loadCoursesByCategory(categoryId) {
        console.log('Loading courses for category:', categoryId);
        showLoading();
        
        fetch(`{{ route('teacher.courses.by-category') }}?category_id=${categoryId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                hideLoading();
                displayCourses(data.courses);
            })
            .catch(error => {
                hideLoading();
                console.error('Error loading courses:', error);
                showErrorMessage();
            });
    }

    // Display courses
    function displayCourses(courses) {
        if (courses.length === 0) {
            categoryCoursesContainer.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <h6>No courses in this category yet</h6>
                    <p class="small">Be the first to create a course in this category!</p>
                </div>
            `;
            return;
        }

        const myCourses = courses.filter(course => course.is_my_course);
        const otherCourses = courses.filter(course => !course.is_my_course);

        let html = '';

        // My Courses Section
        if (myCourses.length > 0) {
            html += `
                <div class="mb-4">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-user-circle me-1"></i>My Courses (${myCourses.length})
                    </h6>
                    <div class="row g-3">
                        ${myCourses.map(course => createCourseCard(course, true)).join('')}
                    </div>
                </div>
            `;
        }

        // Other Courses Section
        if (otherCourses.length > 0) {
            html += `
                <div class="mb-4">
                    <h6 class="text-secondary mb-3">
                        <i class="fas fa-users me-1"></i>Other Courses (${otherCourses.length})
                    </h6>
                    <div class="row g-3">
                        ${otherCourses.map(course => createCourseCard(course, false)).join('')}
                    </div>
                </div>
            `;
        }

        categoryCoursesContainer.innerHTML = html;
    }

    // Create course card HTML
    function createCourseCard(course, isEditable) {
        return `
            <div class="col-md-6">
                <div class="card h-100 ${isEditable ? 'border-primary' : 'border-light'}">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start mb-2">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">${course.title}</h6>
                                <p class="card-text small text-muted mb-2">${course.description}</p>
                            </div>
                            ${course.thumbnail_url ? `
                                <img src="${course.thumbnail_url}" alt="${course.title}" 
                                     class="ms-2 rounded" style="width: 40px; height: 40px; object-fit: cover;">
                            ` : ''}
                        </div>
                        
                        <div class="row text-center mb-2">
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Price</small>
                                <span class="fw-bold small text-success">RM${course.price}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Students</small>
                                <span class="fw-bold small text-primary">${course.enrollments_count}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Lessons</small>
                                <span class="fw-bold small text-info">${course.lessons_count}</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-${course.status === 'published' ? 'success' : 'secondary'} small">
                                    ${course.status.charAt(0).toUpperCase() + course.status.slice(1)}
                                </span>
                                <span class="badge bg-light text-dark small">${course.level}</span>
                            </div>
                            <div class="btn-group" role="group">
                                <a href="${course.view_url}" class="btn btn-outline-primary btn-sm" 
                                   data-bs-toggle="tooltip" title="View Course">
                                    <i class="fas fa-eye"></i>
                                </a>
                                ${isEditable ? `
                                    <a href="${course.edit_url}" class="btn btn-outline-secondary btn-sm" 
                                       data-bs-toggle="tooltip" title="Edit Course">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                ` : `
                                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled 
                                            data-bs-toggle="tooltip" title="Not your course">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                `}
                            </div>
                        </div>
                        
                        ${!isEditable ? `
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>by ${course.instructor_name}
                                </small>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    // Show loading state
    function showLoading() {
        loadingCourses.style.display = 'block';
        categoryCoursesContainer.style.display = 'none';
    }

    // Hide loading state
    function hideLoading() {
        loadingCourses.style.display = 'none';
        categoryCoursesContainer.style.display = 'block';
    }

    // Show default message
    function showDefaultMessage() {
        categoryCoursesContainer.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-folder-open fa-3x mb-3"></i>
                <h6>Select a category to view existing courses</h6>
                <p class="small">You'll be able to edit your own courses and view others for reference</p>
            </div>
        `;
    }

    // Show error message
    function showErrorMessage() {
        categoryCoursesContainer.innerHTML = `
            <div class="text-center text-danger py-5">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <h6>Error loading courses</h6>
                <p class="small">Please try again later</p>
            </div>
        `;
    }

    // Image preview handler
    thumbnailInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });

    // Free course checkbox handler
    isFreeCheckbox.addEventListener('change', function() {
        if (this.checked) {
            priceInput.value = '0';
            priceInput.disabled = true;
            priceInput.style.backgroundColor = '#e9ecef';
        } else {
            priceInput.disabled = false;
            priceInput.style.backgroundColor = '';
            if (priceInput.value === '0') {
                priceInput.value = '';
            }
        }
    });

    // Form submission handler to ensure price is included
    const courseForm = document.getElementById('courseForm');
    const submitBtn = courseForm.querySelector('button[type="submit"]');
    
    courseForm.addEventListener('submit', function(e) {
        // Ensure price field is enabled and has a value before submission
        if (isFreeCheckbox.checked) {
            priceInput.disabled = false;
            priceInput.value = '0';
        } else if (!priceInput.value || priceInput.value === '') {
            priceInput.value = '0';
        }

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
        
        progressBox.innerHTML = `
            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Creating...</span>
            </div>
            <h4 class="mb-3">Creating Course...</h4>
            <p class="text-muted mb-3">Please wait while we create your course</p>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" 
                     style="width: 100%">
                    Processing...
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
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creating...';
    });

    // Load courses on page load if category is already selected
    if (categorySelect.value) {
        loadCoursesByCategory(categorySelect.value);
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.card {
    transition: all 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.border-primary {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.1rem rgba(13, 110, 253, 0.1);
}

#courseDetails {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.btn-group .btn {
    transition: all 0.2s ease-in-out;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}
</style>
@endpush
