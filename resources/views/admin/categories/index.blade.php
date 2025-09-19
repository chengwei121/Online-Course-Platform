@extends('layouts.admin')

@section('title', 'Categories Management')

@section('header')
    <h1 class="h2">
        <i class="fas fa-folder me-3"></i>
        Categories Management
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshTable()" id="refreshBtn">
                <i class="fas fa-sync-alt me-1"></i>
                Refresh
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="exportCategories('pdf')"><i class="fas fa-file-pdf me-2"></i>PDF Report</a></li>
                <li><a class="dropdown-item" href="#" onclick="exportCategories('excel')"><i class="fas fa-file-excel me-2"></i>Excel Export</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>
            Add Category
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Categories Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Categories
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $categories->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
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
                                Active Categories
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $categories->where('courses_count', '>', 0)->count() }}
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
                                Total Courses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $categories->sum('courses_count') }}
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Empty Categories
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $categories->where('courses_count', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card shadow mb-4">
        <div class="card-header modern-grey-header text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-table me-2"></i>
                Categories List
            </h6>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" class="form-control bg-light border-0 small" 
                           placeholder="Search categories..." id="searchInput">
                    <div class="input-group-append">
                        <button class="btn btn-outline-light" type="button" onclick="searchCategories()">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="categoriesTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Name</th>
                            <th width="35%">Description</th>
                            <th width="15%">Courses Count</th>
                            <th width="15%">Created</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="category-icon me-2">
                                        <i class="fas fa-folder text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $category->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $category->slug }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($category->description)
                                    <span class="text-muted">{{ Str::limit($category->description, 80) }}</span>
                                @else
                                    <em class="text-muted">No description</em>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $category->courses_count > 0 ? 'primary' : 'secondary' }} rounded-pill">
                                    {{ $category->courses_count }} 
                                    {{ Str::plural('course', $category->courses_count) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $category->created_at->format('M d, Y') }}
                                    <br>
                                    {{ $category->created_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.show', $category) }}" 
                                       class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($category->courses_count == 0)
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            disabled title="Cannot delete - has courses">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <h5>No Categories Found</h5>
                                    <p>Start by creating your first course category.</p>
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>
                                        Create Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($categories->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} categories
                </div>
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
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
                <p>Are you sure you want to delete the category <strong id="categoryName"></strong>?</p>
                <p class="text-muted mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
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

.category-icon {
    font-size: 1.2rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endpush

@push('scripts')
<script>
function deleteCategory(id, name) {
    document.getElementById('categoryName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/categories/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function refreshTable() {
    window.location.reload();
}

function searchCategories() {
    const searchTerm = document.getElementById('searchInput').value;
    // Simple search implementation - you can enhance this with AJAX
    window.location.href = `{{ route('admin.categories.index') }}?search=${encodeURIComponent(searchTerm)}`;
}

function exportCategories(format) {
    // Implement export functionality
    alert(`Export as ${format.toUpperCase()} - Feature coming soon!`);
}

// Enter key search
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchCategories();
    }
});
</script>
@endpush