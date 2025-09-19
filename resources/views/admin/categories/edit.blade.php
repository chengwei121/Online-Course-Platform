@extends('layouts.admin')

@section('title', 'Edit Category')

@section('header')
    <h1 class="h2">
        <i class="fas fa-edit me-3"></i>
        Edit Category: {{ $category->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Categories
            </a>
            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-eye me-1"></i>
                View Details
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-edit me-2"></i>
                        Edit Category Information
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" id="categoryForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label required">Category Name</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $category->name) }}" 
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
                                           value="{{ old('slug', $category->slug) }}" 
                                           placeholder="auto-generated"
                                           readonly>
                                    <div class="form-text">
                                        This will be automatically updated when you change the name
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
                                              placeholder="Enter category description (optional)">{{ old('description', $category->description) }}</textarea>
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
                                            Category Details
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Created:</strong> {{ $category->created_at->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Last Updated:</strong> {{ $category->updated_at->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Courses in this category:</strong> 
                                                    <span class="badge bg-primary">{{ $category->courses->count() }}</span>
                                                </small>
                                            </div>
                                        </div>
                                        @if($category->courses->count() > 0)
                                        <div class="mt-2">
                                            <small class="text-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Changing the category name or slug may affect existing course URLs
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>
                                            Cancel
                                        </a>
                                    </div>
                                    <div>
                                        @if($category->courses->count() == 0)
                                        <button type="button" class="btn btn-outline-danger me-2" 
                                                onclick="deleteCategory()">
                                            <i class="fas fa-trash me-1"></i>
                                            Delete Category
                                        </button>
                                        @endif
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-1"></i>
                                            Update Category
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($category->courses->count() == 0)
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category <strong>{{ $category->name }}</strong>?</p>
                <p class="text-muted mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Delete Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
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
            <i class="fas fa-spinner fa-spin me-1"></i>
            Updating...
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
            Update Category
        `;
    }

    function showError(message) {
        alert(message); // Simple alert for now
    }

    // Character counter for description
    const descriptionInput = document.getElementById('description');
    const maxLength = 1000;
    
    // Create character counter
    const counter = document.createElement('small');
    counter.className = 'form-text text-muted mt-1';
    counter.innerHTML = `<span id="charCount">${descriptionInput.value.length}</span>/${maxLength} characters`;
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

function deleteCategory() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush