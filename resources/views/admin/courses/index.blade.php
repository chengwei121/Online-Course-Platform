@extends('layouts.admin')

@section('title', 'Courses Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="card-title mb-2">
                                <i class="fas fa-graduation-cap me-3"></i>
                                Course Management
                            </h1>
                            <p class="card-text mb-0">Manage all courses, instructors, and content</p>
                        </div>
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-plus me-2"></i>Create Course
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-2x mb-2"></i>
                    <h3 class="card-title">{{ $courses->total() }}</h3>
                    <p class="card-text">Total Courses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h3 class="card-title">{{ $courses->where('status', 'published')->count() }}</h3>
                    <p class="card-text">Published</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-edit fa-2x mb-2"></i>
                    <h3 class="card-title">{{ $courses->where('status', 'draft')->count() }}</h3>
                    <p class="card-text">Drafts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-tags fa-2x mb-2"></i>
                    <h3 class="card-title">{{ $categories->count() }}</h3>
                    <p class="card-text">Categories</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Search & Filter
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.courses.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Search Courses</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="Search by title or description..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="instructor" class="form-label">Instructor</label>
                                <select name="instructor" id="instructor" class="form-select">
                                    <option value="">All Instructors</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ request('instructor') == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="d-grid w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                        @if(request()->hasAny(['search', 'status', 'category', 'instructor']))
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times me-1"></i>Clear Filters
                                    </a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>All Courses
                    </h5>
                    <span class="badge bg-primary fs-6">{{ $courses->total() }} courses</span>
                </div>
                <div class="card-body p-0">
                    @if($courses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%;">Course Details</th>
                                        <th style="width: 15%;">Instructor</th>
                                        <th style="width: 12%;">Category</th>
                                        <th style="width: 10%;">Price</th>
                                        <th style="width: 8%;">Students</th>
                                        <th style="width: 8%;">Status</th>
                                        <th style="width: 7%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courses as $course)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $course->thumbnail ?? asset('images/course-placeholder.jpg') }}" 
                                                         alt="{{ $course->title }}" 
                                                         class="rounded me-3" 
                                                         style="width: 80px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">{{ Str::limit($course->title, 40) }}</h6>
                                                        <p class="text-muted mb-1 small">{{ Str::limit($course->description, 60) }}</p>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge bg-light text-dark">{{ $course->level_label }}</span>
                                                            @if($course->learning_hours)
                                                                <small class="text-muted">
                                                                    <i class="fas fa-clock me-1"></i>{{ $course->learning_hours }}h
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 30px; height: 30px;">
                                                        <span class="text-white fw-bold small">{{ substr($course->instructor->name, 0, 1) }}</span>
                                                    </div>
                                                    <span class="fw-medium">{{ $course->instructor->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $course->category->name }}</span>
                                            </td>
                                            <td>
                                                @if($course->is_free)
                                                    <span class="badge bg-success">FREE</span>
                                                @else
                                                    <span class="fw-bold text-success">${{ number_format($course->price, 2) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-users text-muted me-1"></i>
                                                    <span class="fw-bold">{{ $course->enrollments->count() }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($course->status == 'published')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Published
                                                    </span>
                                                @elseif($course->status == 'draft')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-edit me-1"></i>Draft
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-archive me-1"></i>Archived
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.courses.show', $course) }}" 
                                                       class="btn btn-info btn-sm" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.courses.edit', $course) }}" 
                                                       class="btn btn-warning btn-sm" title="Edit Course">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.courses.toggle-status', $course) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-{{ $course->status == 'published' ? 'secondary' : 'success' }} btn-sm" 
                                                                title="{{ $course->status == 'published' ? 'Unpublish' : 'Publish' }}">
                                                            <i class="fas fa-{{ $course->status == 'published' ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                    @if($course->enrollments->count() == 0)
                                                        <button type="button" 
                                                                class="btn btn-danger btn-sm" 
                                                                onclick="confirmDelete('{{ $course->id }}', '{{ $course->title }}')"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($courses->hasPages())
                            <div class="card-footer bg-light">
                                <div class="row align-items-center g-3">
                                    <!-- Results Info -->
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            <span class="text-muted small">
                                                Showing 
                                                <strong>{{ $courses->firstItem() }}</strong> 
                                                to 
                                                <strong>{{ $courses->lastItem() }}</strong> 
                                                of 
                                                <strong>{{ $courses->total() }}</strong> 
                                                results
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Pagination Controls -->
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end">
                                            <nav aria-label="Course pagination">
                                                <ul class="pagination pagination-sm mb-0" style="gap: 4px;">
                                                    {{-- Previous Page Link --}}
                                                    @if ($courses->onFirstPage())
                                                        <li class="page-item disabled">
                                                            <span class="page-link px-2 py-1" style="font-size: 0.8rem;">
                                                                <i class="fas fa-chevron-left me-1"></i>Prev
                                                            </span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link px-2 py-1" href="{{ $courses->appends(request()->query())->previousPageUrl() }}" style="font-size: 0.8rem;">
                                                                <i class="fas fa-chevron-left me-1"></i>Prev
                                                            </a>
                                                        </li>
                                                    @endif

                                                    {{-- Pagination Elements --}}
                                                    @php
                                                        $start = max($courses->currentPage() - 2, 1);
                                                        $end = min($start + 4, $courses->lastPage());
                                                        $start = max($end - 4, 1);
                                                    @endphp

                                                    @if($start > 1)
                                                        <li class="page-item">
                                                            <a class="page-link px-2 py-1" href="{{ $courses->appends(request()->query())->url(1) }}" style="font-size: 0.8rem; min-width: 32px; text-align: center;">1</a>
                                                        </li>
                                                        @if($start > 2)
                                                            <li class="page-item disabled">
                                                                <span class="page-link px-2 py-1" style="font-size: 0.8rem;">...</span>
                                                            </li>
                                                        @endif
                                                    @endif

                                                    @for ($page = $start; $page <= $end; $page++)
                                                        @if ($page == $courses->currentPage())
                                                            <li class="page-item active">
                                                                <span class="page-link bg-primary border-primary px-2 py-1" style="font-size: 0.8rem; min-width: 32px; text-align: center;">
                                                                    {{ $page }}
                                                                    <span class="visually-hidden">(current)</span>
                                                                </span>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link px-2 py-1" href="{{ $courses->appends(request()->query())->url($page) }}" style="font-size: 0.8rem; min-width: 32px; text-align: center;">{{ $page }}</a>
                                                            </li>
                                                        @endif
                                                    @endfor

                                                    @if($end < $courses->lastPage())
                                                        @if($end < $courses->lastPage() - 1)
                                                            <li class="page-item disabled">
                                                                <span class="page-link px-2 py-1" style="font-size: 0.8rem;">...</span>
                                                            </li>
                                                        @endif
                                                        <li class="page-item">
                                                            <a class="page-link px-2 py-1" href="{{ $courses->appends(request()->query())->url($courses->lastPage()) }}" style="font-size: 0.8rem; min-width: 32px; text-align: center;">{{ $courses->lastPage() }}</a>
                                                        </li>
                                                    @endif

                                                    {{-- Next Page Link --}}
                                                    @if ($courses->hasMorePages())
                                                        <li class="page-item">
                                                            <a class="page-link px-2 py-1" href="{{ $courses->appends(request()->query())->nextPageUrl() }}" style="font-size: 0.8rem;">
                                                                Next<i class="fas fa-chevron-right ms-1"></i>
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li class="page-item disabled">
                                                            <span class="page-link px-2 py-1" style="font-size: 0.8rem;">
                                                                Next<i class="fas fa-chevron-right ms-1"></i>
                                                            </span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quick Jump (for large datasets) -->
                                @if($courses->lastPage() > 10)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <span class="text-muted" style="font-size: 0.8rem;">Jump to page:</span>
                                                <div class="input-group" style="width: 100px;">
                                                    <input type="number" 
                                                           class="form-control form-control-sm" 
                                                           id="jumpToPage" 
                                                           min="1" 
                                                           max="{{ $courses->lastPage() }}" 
                                                           value="{{ $courses->currentPage() }}"
                                                           placeholder="Page"
                                                           style="font-size: 0.8rem; text-align: center;">
                                                    <button class="btn btn-outline-primary btn-sm px-2" 
                                                            type="button" 
                                                            onclick="jumpToPage()"
                                                            style="font-size: 0.8rem;">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-graduation-cap display-1 text-muted mb-3"></i>
                                <h4 class="text-muted">No courses found</h4>
                                <p class="text-muted">Start by creating your first course or adjust your filters.</p>
                            </div>
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Create Your First Course
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Are you sure you want to delete the course "<span id="courseTitle" class="fw-bold"></span>"?
                </div>
                <p class="text-danger">
                    <small><strong>Warning:</strong> This action cannot be undone and will permanently remove all course data.</small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Course
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(courseId, courseTitle) {
    document.getElementById('courseTitle').textContent = courseTitle;
    document.getElementById('deleteForm').action = `/admin/courses/${courseId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Auto-submit form on filter change for better UX
document.querySelectorAll('select[name="status"], select[name="category"], select[name="instructor"]').forEach(select => {
    select.addEventListener('change', function() {
        this.form.submit();
    });
});

// Jump to page functionality
function jumpToPage() {
    const pageInput = document.getElementById('jumpToPage');
    const page = parseInt(pageInput.value);
    const maxPage = parseInt(pageInput.getAttribute('max'));
    
    if (page >= 1 && page <= maxPage) {
        // Construct URL with current query parameters
        const url = new URL(window.location);
        url.searchParams.set('page', page);
        window.location.href = url.toString();
    } else {
        // Show error message
        pageInput.classList.add('is-invalid');
        setTimeout(() => {
            pageInput.classList.remove('is-invalid');
        }, 2000);
    }
}

// Allow Enter key to trigger jump
document.addEventListener('DOMContentLoaded', function() {
    const jumpInput = document.getElementById('jumpToPage');
    if (jumpInput) {
        jumpInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                jumpToPage();
            }
        });
    }
});
</script>
@endpush
@endsection
