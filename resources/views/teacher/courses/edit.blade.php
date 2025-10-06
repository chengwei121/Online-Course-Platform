@extends('teacher.layouts.app')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Course Edit Form -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Course: {{ $course->title }}
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

                    <form action="{{ route('teacher.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
                        @csrf
                        @method('PUT')
                        
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
                                            {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Course Details -->
                        <hr class="my-4">
                        <h6 class="text-warning mb-3">
                            <i class="fas fa-book me-1"></i>Course Information
                        </h6>

                        <!-- Course Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Course Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $course->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Course Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $course->description) }}</textarea>
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
                                           id="price" name="price" value="{{ old('price', $course->price) }}" 
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
                                    <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
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
                                           id="learning_hours" name="learning_hours" value="{{ old('learning_hours', $course->learning_hours) }}" 
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
                                           value="1" {{ old('is_free', $course->is_free) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_free">
                                        <i class="fas fa-gift me-1"></i>Make this course free
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Course Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Course Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status">
                                <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $course->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Skills to Learn -->
                        <div class="mb-3">
                            <label for="skills_to_learn" class="form-label">Skills Students Will Learn</label>
                            <textarea class="form-control @error('skills_to_learn') is-invalid @enderror" 
                                      id="skills_to_learn" name="skills_to_learn" rows="3" 
                                      placeholder="Enter skills separated by commas (e.g., HTML, CSS, JavaScript)">{{ old('skills_to_learn', is_array($course->skills_to_learn) ? implode(', ', $course->skills_to_learn) : $course->skills_to_learn) }}</textarea>
                            @error('skills_to_learn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Separate multiple skills with commas</div>
                        </div>

                        <!-- Current Thumbnail -->
                        @if($course->thumbnail)
                            <div class="mb-3">
                                <label class="form-label">Current Thumbnail</label>
                                <div class="d-flex align-items-center">
                                    <img src="{{ filter_var($course->thumbnail, FILTER_VALIDATE_URL) ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}" 
                                         alt="Current thumbnail" class="img-thumbnail me-3" style="max-height: 100px;">
                                    <div>
                                        <p class="mb-1 text-muted">Current thumbnail image</p>
                                        <small class="text-muted">Upload a new image below to replace this one</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Course Thumbnail -->
                        <div class="mb-4">
                            <label for="thumbnail" class="form-label">
                                {{ $course->thumbnail ? 'Update' : 'Add' }} Course Thumbnail
                            </label>
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
                            <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i>Update Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Course Statistics -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Course Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h4 class="text-primary mb-1">{{ $course->enrollments_count ?? 0 }}</h4>
                            <small class="text-muted">Students Enrolled</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-success mb-1">{{ $course->lessons_count ?? 0 }}</h4>
                            <small class="text-muted">Lessons</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning mb-1">RM{{ number_format($course->price, 2) }}</h4>
                            <small class="text-muted">Course Price</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info mb-1">{{ $course->learning_hours }}h</h4>
                            <small class="text-muted">Learning Hours</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>View Course Details
                        </a>
                        @if($course->lessons_count > 0)
                            <a href="{{ route('teacher.courses.lessons.index', ['course' => $course->id]) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-play me-1"></i>Manage Lessons
                            </a>
                        @else
                            <a href="{{ route('teacher.courses.lessons.create', ['course' => $course->id]) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-plus me-1"></i>Add First Lesson
                            </a>
                        @endif
                        <a href="{{ route('teacher.courses.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-list me-1"></i>All My Courses
                        </a>
                    </div>
                </div>
            </div>

            <!-- Course Status Info -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Status Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Current Status:</strong>
                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : ($course->status === 'draft' ? 'warning' : 'secondary') }} ms-2">
                            {{ ucfirst($course->status) }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong>Created:</strong>
                        <span class="text-muted">{{ $course->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Last Updated:</strong>
                        <span class="text-muted">{{ $course->updated_at->format('M d, Y') }}</span>
                    </div>
                    @if($course->status === 'draft')
                        <div class="mt-3">
                            <div class="alert alert-warning alert-sm">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Draft Mode:</strong> This course is not visible to students yet. Change status to "Published" to make it available.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const thumbnailInput = document.getElementById('thumbnail');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const isFreeCheckbox = document.getElementById('is_free');
    const priceInput = document.getElementById('price');

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

    // Initialize free course state
    if (isFreeCheckbox.checked) {
        priceInput.disabled = true;
        priceInput.style.backgroundColor = '#e9ecef';
    }

    // Form submission handler
    const courseForm = document.getElementById('courseForm');
    courseForm.addEventListener('submit', function(e) {
        // Ensure price field is enabled and has a value before submission
        if (isFreeCheckbox.checked) {
            priceInput.disabled = false;
            priceInput.value = '0';
        } else if (!priceInput.value || priceInput.value === '') {
            priceInput.value = '0';
        }
    });
});
</script>

<style>
.card {
    transition: all 0.2s ease-in-out;
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75em;
}

#imagePreview {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush