@extends('layouts.admin')

@section('title', 'Create Category')

@section('header')
    <h1 class="h2">
        <i class="fas fa-plus me-3"></i>
        Create New Category
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Back to Categories
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-folder-plus me-2"></i>
                        Category Information
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST" id="categoryForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label required">Category Name</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Enter category name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        This will be the display name for your category
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">URL Slug</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="slug" 
                                           name="slug" 
                                           value="{{ old('slug') }}" 
                                           placeholder="auto-generated"
                                           readonly>
                                    <div class="form-text">
                                        This will be automatically generated from the category name
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Enter category description (optional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Provide a brief description of what courses belong to this category
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card border-left-info">
                                    <div class="card-body">
                                        <h6 class="card-title text-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Category Guidelines
                                        </h6>
                                        <ul class="mb-0 text-muted">
                                            <li>Choose a clear and descriptive name for your category</li>
                                            <li>Category names should be unique across the platform</li>
                                            <li>The URL slug will be automatically generated from the name</li>
                                            <li>You can edit categories later, but it may affect existing courses</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-1"></i>
                                        Create Category
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Modern Grey Card Header */
.modern-grey-header {
    background: linear-gradient(135deg, #64748b 0%, #475569 50%, #334155 100%);
    border-bottom: 3px solid #1e293b;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.required::after {
    content: " *";
    color: #e74c3c;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}

.loading-spinner {
    display: none;
}

.loading .loading-spinner {
    display: inline-block;
}

.loading .btn-text {
    display: none;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const form = document.getElementById('categoryForm');
    const submitBtn = document.getElementById('submitBtn');

    // Auto-generate slug from name
    nameInput.addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '') // Remove invalid chars
            .replace(/\s+/g, '-')        // Replace spaces with -
            .replace(/-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')          // Trim - from start of text
            .replace(/-+$/, '');         // Trim - from end of text
        
        slugInput.value = slug;
    });

    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="loading-spinner">
                <i class="fas fa-spinner fa-spin me-1"></i>
            </span>
            <span class="btn-text">
                <i class="fas fa-save me-1"></i>
                Create Category
            </span>
            Creating...
        `;

        // Validate form
        const name = nameInput.value.trim();
        if (name.length < 2) {
            e.preventDefault();
            showError('Category name must be at least 2 characters long');
            resetSubmitButton();
            return;
        }

        if (name.length > 255) {
            e.preventDefault();
            showError('Category name must not exceed 255 characters');
            resetSubmitButton();
            return;
        }
    });

    function resetSubmitButton() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `
            <i class="fas fa-save me-1"></i>
            Create Category
        `;
    }

    function showError(message) {
        // Create or update error alert
        let errorAlert = document.querySelector('.alert-danger');
        if (!errorAlert) {
            errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger alert-dismissible fade show';
            errorAlert.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span class="error-message">${message}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            form.insertBefore(errorAlert, form.firstChild);
        } else {
            errorAlert.querySelector('.error-message').textContent = message;
        }

        // Scroll to error
        errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Real-time validation
    nameInput.addEventListener('blur', function() {
        const name = this.value.trim();
        if (name && name.length < 2) {
            this.classList.add('is-invalid');
            let feedback = this.parentNode.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                this.parentNode.appendChild(feedback);
            }
            feedback.textContent = 'Category name must be at least 2 characters long';
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Character counter for description
    const descriptionInput = document.getElementById('description');
    const maxLength = 1000;
    
    // Create character counter
    const counter = document.createElement('small');
    counter.className = 'form-text text-muted mt-1';
    counter.innerHTML = `<span id="charCount">0</span>/${maxLength} characters`;
    descriptionInput.parentNode.appendChild(counter);
    
    const charCountSpan = document.getElementById('charCount');
    
    descriptionInput.addEventListener('input', function() {
        const length = this.value.length;
        charCountSpan.textContent = length;
        
        if (length > maxLength * 0.9) {
            counter.classList.remove('text-muted');
            counter.classList.add('text-warning');
        } else if (length > maxLength) {
            counter.classList.remove('text-warning');
            counter.classList.add('text-danger');
        } else {
            counter.classList.remove('text-warning', 'text-danger');
            counter.classList.add('text-muted');
        }
    });
});
</script>
@endpush