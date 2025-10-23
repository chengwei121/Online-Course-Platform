@extends('layouts.admin')

@section('title', 'Category Details')

@section('header')
    <h1 class="h2">
        <i class="fas fa-folder me-3"></i>
        {{ $category->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Categories
            </a>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-warning">
                <i class="fas fa-edit me-1"></i>
                Edit Category
            </a>
        </div>
        @if($stats['total_courses'] == 0)
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCategory()">
            <i class="fas fa-trash me-1"></i>
            Delete Category
        </button>
        @endif
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Category Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Courses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_courses'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Published Courses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['published_courses'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Instructors
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_instructors'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Enrollments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_enrollments'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Information -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Category Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Name</h6>
                            <p class="mb-3">{{ $category->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">URL Slug</h6>
                            <p class="mb-3">
                                <code>{{ $category->slug }}</code>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary">Description</h6>
                            @if($category->description)
                                <p class="mb-0">{{ $category->description }}</p>
                            @else
                                <p class="text-muted mb-0">No description provided</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calendar me-2"></i>
                        Timestamps
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Created</h6>
                        <p class="mb-1">{{ $category->created_at->format('M d, Y \a\t H:i') }}</p>
                        <small class="text-muted">{{ $category->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-primary">Last Updated</h6>
                        <p class="mb-1">{{ $category->updated_at->format('M d, Y \a\t H:i') }}</p>
                        <small class="text-muted">{{ $category->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses in Category -->
    <div class="card shadow">
        <div class="card-header modern-grey-header text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-book me-2"></i>
                Courses in this Category ({{ $stats['total_courses'] }})
            </h6>
            @if($stats['total_courses'] > 0)
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-1"></i>
                    Filter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="filterCourses('all')">All Courses</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterCourses('published')">Published Only</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterCourses('draft')">Draft Only</a></li>
                </ul>
            </div>
            @endif
        </div>
        <div class="card-body">
            @if($stats['total_courses'] > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>Status</th>
                            <th>Enrollments</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr data-status="{{ $course->status }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="course-icon me-2">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $course->title }}</strong>
                                        @if($course->price > 0)
                                        <br><small class="text-success">RM{{ number_format($course->price, 2) }}</small>
                                        @else
                                        <br><small class="text-info">Free</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($course->instructor)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle me-2 text-muted"></i>
                                        {{ $course->instructor->name }}
                                    </div>
                                @else
                                    <span class="text-muted">No instructor assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'warning' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $course->enrollments_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $course->created_at->format('M d, Y') }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('admin.courses.show', $course) }}" 
                                   class="btn btn-sm btn-outline-info" title="View Course Details">
                                    <i class="fas fa-eye me-1"></i>
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($courses->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
                <div class="text-muted small">
                    Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} courses
                </div>
                <div>
                    {{ $courses->links() }}
                </div>
            </div>
            @endif
            @else
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Courses in this Category</h5>
                <p class="text-muted mb-3">This category doesn't have any courses yet. Teachers can create courses in this category.</p>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-primary">
                    <i class="fas fa-eye me-1"></i>
                    View All Courses
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($stats['total_courses'] == 0)
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

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,0.025);
}

.course-icon {
    font-size: 1.2rem;
}

code {
    padding: 2px 4px;
    font-size: 87.5%;
    color: #e83e8c;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}
</style>
@endpush

@push('scripts')
<script>
function deleteCategory() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function filterCourses(status) {
    const rows = document.querySelectorAll('tbody tr[data-status]');
    
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update filter button text
    const filterText = status === 'all' ? 'All Courses' : 
                      status === 'published' ? 'Published Only' : 'Draft Only';
    
    document.querySelector('.dropdown-toggle').innerHTML = `
        <i class="fas fa-filter me-1"></i>
        ${filterText}
    `;
}
</script>
@endpush