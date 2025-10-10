@extends('teacher.layouts.app')

@section('title', 'My Courses')
@section('page-title', 'My Courses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Courses</h2>
    <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Create New Course
    </a>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('teacher.courses.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search Courses</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by title or description...">
                </div>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="per_page_filter" class="form-label">Show</label>
                <select class="form-select" id="per_page_filter" name="per_page">
                    <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12 per page</option>
                    <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 per page</option>
                    <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48 per page</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('teacher.courses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

@if($courses->count() > 0)
    <div class="row">
        @foreach($courses as $course)
        <div class="col-md-4 col-lg-3 mb-3">
            <div class="card h-100 shadow-sm">
                @if($course->thumbnail)
                    <img src="{{ $course->thumbnail_url }}" class="card-img-top" alt="{{ $course->title }}" style="height: 120px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                        <i class="fas fa-book fa-2x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column p-3">
                    <h6 class="card-title mb-2 fw-bold">{{ Str::limit($course->title, 40) }}</h6>
                    <p class="card-text text-muted small flex-grow-1 mb-2">{{ Str::limit($course->description, 60) }}</p>
                    
                    <div class="mb-2">
                        <div class="row text-center g-1">
                            <div class="col-4">
                                <div class="bg-light rounded p-1">
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Students</small>
                                    <span class="fw-bold small text-primary">{{ $course->enrollments_count }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light rounded p-1">
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Lessons</small>
                                    <span class="fw-bold small text-info">{{ $course->lessons_count }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light rounded p-1">
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Price</small>
                                    @if($course->price == 0)
                                        <span class="fw-bold small text-success">Free</span>
                                    @else
                                        <span class="fw-bold small text-success">RM{{ number_format($course->price, 0) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'secondary' }} small">
                            {{ ucfirst($course->status) }}
                        </span>
                        <span class="badge bg-light text-dark small">{{ ucfirst($course->level ?? 'beginner') }}</span>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent p-2">
                    <div class="d-grid gap-1">
                        <!-- Primary Actions Row -->
                        <div class="btn-group" role="group" aria-label="Course actions">
                            <a href="{{ route('teacher.courses.show', $course) }}" 
                               class="btn btn-outline-primary btn-sm flex-fill" 
                               data-bs-toggle="tooltip" data-bs-placement="top" title="View Course Details">
                                <i class="fas fa-eye me-1"></i>
                                <span class="d-none d-lg-inline">View</span>
                            </a>
                            <a href="{{ route('teacher.courses.edit', $course) }}" 
                               class="btn btn-outline-secondary btn-sm flex-fill" 
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Course">
                                <i class="fas fa-edit me-1"></i>
                                <span class="d-none d-lg-inline">Edit</span>
                            </a>
                        </div>
                        
                        <!-- Secondary Actions Row -->
                        <div class="btn-group" role="group" aria-label="Course status actions">
                            <form method="POST" action="{{ route('teacher.courses.toggle-status', $course) }}" class="flex-fill">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-{{ $course->status === 'published' ? 'warning' : 'success' }} btn-sm w-100" 
                                        data-bs-toggle="tooltip" data-bs-placement="top" 
                                        title="{{ $course->status === 'published' ? 'Unpublish Course' : 'Publish Course' }}">
                                    <i class="fas fa-{{ $course->status === 'published' ? 'pause' : 'play' }} me-1"></i>
                                    <span class="small">{{ $course->status === 'published' ? 'Unpublish' : 'Publish' }}</span>
                                </button>
                            </form>
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm flex-fill" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $course->id }}"
                                    data-bs-tooltip="tooltip" 
                                    data-bs-placement="top" 
                                    title="Delete Course">
                                <i class="fas fa-trash me-1"></i>
                                <span class="d-none d-lg-inline small">Delete</span>
                            </button>
                        </div>
                        
                        <!-- Additional Quick Actions -->
                        <div class="btn-group btn-group-sm" role="group" aria-label="Quick actions">
                            <a href="#" class="btn btn-light btn-sm" 
                               data-bs-toggle="tooltip" data-bs-placement="top" title="View Students">
                                <i class="fas fa-users text-primary"></i>
                                <span class="small ms-1">{{ $course->enrollments_count }}</span>
                            </a>
                            <a href="#" class="btn btn-light btn-sm" 
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Lessons">
                                <i class="fas fa-book-open text-info"></i>
                                <span class="small ms-1">{{ $course->lessons_count }}</span>
                            </a>
                            <button type="button" class="btn btn-light btn-sm"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Course Analytics">
                                <i class="fas fa-chart-line text-success"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Delete Modal -->
        <div class="modal fade" id="deleteModal{{ $course->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $course->id }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white border-0">
                        <h5 class="modal-title" id="deleteModalLabel{{ $course->id }}">
                            <i class="fas fa-exclamation-triangle me-2"></i>Delete Course
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                            <h6 class="fw-bold">Are you sure you want to delete this course?</h6>
                        </div>
                        
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <strong>Warning:</strong> This action cannot be undone. All course data, lessons, and student progress will be permanently deleted.
                            </div>
                        </div>
                        
                        <div class="bg-light rounded p-3 mb-3">
                            <h6 class="fw-bold text-dark mb-2">Course Details:</h6>
                            <div class="row small">
                                <div class="col-6">
                                    <strong>Title:</strong><br>
                                    <span class="text-muted">{{ Str::limit($course->title, 30) }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Students:</strong><br>
                                    <span class="text-muted">{{ $course->enrollments_count }} enrolled</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmDelete{{ $course->id }}" required>
                            <label class="form-check-label small" for="confirmDelete{{ $course->id }}">
                                I understand that this action is permanent and cannot be undone
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" id="deleteBtn{{ $course->id }}" disabled>
                                <i class="fas fa-trash me-1"></i>Delete Course
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Enhanced Pagination -->
    @if($courses->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <!-- Results Info -->
                <div class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Showing <strong>{{ $courses->firstItem() }}</strong> to <strong>{{ $courses->lastItem() }}</strong> 
                    of <strong>{{ $courses->total() }}</strong> courses
                    @if(request('search'))
                        matching "<strong>{{ request('search') }}</strong>"
                    @endif
                </div>
                
                <!-- Pagination Links -->
                <nav aria-label="Course pagination">
                    <div class="pagination-wrapper">
                        {{ $courses->onEachSide(2)->links('custom.pagination') }}
                    </div>
                </nav>
                
                <!-- Quick Jump -->
                @if($courses->lastPage() > 10)
                <div class="d-flex align-items-center">
                    <label for="jumpToPage" class="form-label me-2 mb-0 small text-muted">Jump to:</label>
                    <select id="jumpToPage" class="form-select form-select-sm" style="width: auto;" onchange="jumpToPage(this.value)">
                        @for($i = 1; $i <= $courses->lastPage(); $i++)
                            <option value="{{ $i }}" {{ $courses->currentPage() == $i ? 'selected' : '' }}>
                                Page {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
@else
    @if(request()->hasAny(['search', 'status']) && (request('search') || request('status') !== 'all'))
        <!-- No results found with filters -->
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h3>No courses found</h3>
            <p class="text-muted mb-4">
                No courses match your current filters. Try adjusting your search criteria.
            </p>
            <div class="mb-3">
                @if(request('search'))
                    <span class="badge bg-primary">Search: "{{ request('search') }}"</span>
                @endif
                @if(request('status') && request('status') !== 'all')
                    <span class="badge bg-secondary">Status: {{ ucfirst(request('status')) }}</span>
                @endif
            </div>
            <a href="{{ route('teacher.courses.index') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-times me-1"></i>Clear Filters
            </a>
            <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create New Course
            </a>
        </div>
    @else
        <!-- No courses at all -->
        <div class="text-center py-5">
            <i class="fas fa-book fa-4x text-muted mb-3"></i>
            <h3>No courses yet</h3>
            <p class="text-muted mb-4">Start building your online course library by creating your first course.</p>
            <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Create Your First Course
            </a>
        </div>
    @endif
@endif
@endsection

@push('scripts')
<script>
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page
    window.location.href = url.toString();
}

function jumpToPage(page) {
    const url = new URL(window.location);
    url.searchParams.set('page', page);
    window.location.href = url.toString();
}

// Auto-submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="courses"]');
    const autoSubmitElements = ['#per_page_filter'];
    
    autoSubmitElements.forEach(selector => {
        const element = document.querySelector(selector);
        if (element) {
            element.addEventListener('change', function() {
                form.submit();
            });
        }
    });
    
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle delete confirmation checkboxes
    document.querySelectorAll('[id^="confirmDelete"]').forEach(function(checkbox) {
        const courseId = checkbox.id.replace('confirmDelete', '');
        const deleteBtn = document.getElementById('deleteBtn' + courseId);
        
        checkbox.addEventListener('change', function() {
            deleteBtn.disabled = !this.checked;
            
            if (this.checked) {
                deleteBtn.classList.remove('btn-danger');
                deleteBtn.classList.add('btn-outline-danger');
            } else {
                deleteBtn.classList.remove('btn-outline-danger');
                deleteBtn.classList.add('btn-danger');
            }
        });
    });
    
    // Add custom classes to pagination elements
    const paginationNav = document.querySelector('.pagination-wrapper .pagination');
    if (paginationNav) {
        paginationNav.classList.add('pagination-sm');
        
        // Style pagination items
        const paginationItems = paginationNav.querySelectorAll('.page-item');
        paginationItems.forEach(item => {
            const link = item.querySelector('.page-link');
            if (link) {
                link.style.borderRadius = '6px';
                link.style.margin = '0 2px';
                link.style.border = '1px solid #dee2e6';
            }
            
            if (item.classList.contains('active')) {
                const activeLink = item.querySelector('.page-link');
                if (activeLink) {
                    activeLink.style.backgroundColor = '#0d6efd';
                    activeLink.style.borderColor = '#0d6efd';
                    activeLink.style.color = 'white';
                }
            }
        });
    }
    
    // Add search functionality with enter key
    const searchInput = document.querySelector('#search');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                form.submit();
            }
        });
    }
    
    // Add hover effects to action buttons
    document.querySelectorAll('.btn-group .btn').forEach(function(btn) {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
            this.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>

<style>
.pagination-wrapper .pagination {
    margin-bottom: 0;
}

.pagination-wrapper .page-item .page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    transition: all 0.2s ease-in-out;
}

.pagination-wrapper .page-item .page-link:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
    transform: translateY(-1px);
}

.pagination-wrapper .page-item.active .page-link {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.25);
}

.pagination-wrapper .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

@media (max-width: 768px) {
    .pagination-wrapper {
        overflow-x: auto;
        padding: 10px 0;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 15px;
    }
    
    .d-flex.justify-content-between > div {
        text-align: center;
    }
}
</style>
@endpush
