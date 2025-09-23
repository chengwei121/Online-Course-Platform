@extends('layouts.admin')

@section('title', 'Teachers Management')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chalkboard-teacher me-2"></i>Teachers Management
        </h1>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i>Add New Teacher
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Teachers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
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
                                Active Teachers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                Inactive Teachers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
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
                                With Courses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['with_courses'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Teachers Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table me-2"></i>Teachers List
                    </h6>
                </div>
                <div class="col-md-6 text-end">
                    <span class="text-muted">
                        Showing {{ $teachers->firstItem() ?? 0 }} to {{ $teachers->lastItem() ?? 0 }} 
                        of {{ $teachers->total() }} results
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <div class="mb-4">
                <form method="GET" action="{{ route('admin.teachers.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label fw-semibold">
                            <i class="fas fa-search me-1"></i>Search
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   placeholder="Search by name, email, department..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label fw-semibold">
                            <i class="fas fa-toggle-on me-1"></i>Status
                        </label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label fw-semibold">
                            <i class="fas fa-building me-1"></i>Department
                        </label>
                        <select name="department" id="department" class="form-select">
                            <option value="">All Departments</option>
                            <option value="Computer Science & IT" {{ request('department') == 'Computer Science & IT' ? 'selected' : '' }}>Computer Science & IT</option>
                            <option value="Engineering" {{ request('department') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="Business & Management" {{ request('department') == 'Business & Management' ? 'selected' : '' }}>Business & Management</option>
                            <option value="Mathematics & Statistics" {{ request('department') == 'Mathematics & Statistics' ? 'selected' : '' }}>Mathematics & Statistics</option>
                            <option value="Science & Technology" {{ request('department') == 'Science & Technology' ? 'selected' : '' }}>Science & Technology</option>
                            <option value="Arts & Design" {{ request('department') == 'Arts & Design' ? 'selected' : '' }}>Arts & Design</option>
                            <option value="Other" {{ request('department') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
                <hr class="my-4">
            </div>
            @if($teachers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead class="table-dark">
                                                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th width="15%">Teacher</th>
                                <th width="20%">Contact</th>
                                <th class="text-center" width="15%">Department</th>
                                <th class="text-center" width="15%">Qualification</th>
                                <th class="text-center" width="10%">Courses</th>
                                <th class="text-center" width="10%">Status</th>
                                <th class="text-center" width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                                <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration + ($teachers->currentPage() - 1) * $teachers->perPage() }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="teacher-avatar me-3">
                                                    @if($teacher->profile_picture)
                                                        <img src="{{ Storage::url($teacher->profile_picture) }}" class="rounded-circle border" width="40" height="40" alt="Profile">
                                                    @else
                                                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; font-weight: bold; font-size: 14px;" title="{{ $teacher->name }}">
                                                            @php
                                                                $nameParts = explode(' ', trim($teacher->name));
                                                                if (count($nameParts) >= 2) {
                                                                    echo strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
                                                                } else {
                                                                    echo strtoupper(substr($teacher->name, 0, 2));
                                                                }
                                                            @endphp
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">{{ $teacher->name }}</div>
                                                    <small class="text-muted">ID: {{ $teacher->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="text-dark">{{ $teacher->email }}</div>
                                            @if($teacher->phone)
                                                <small class="text-muted d-block">{{ $teacher->phone }}</small>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-info text-white px-2 py-1">{{ $teacher->department }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <small class="text-muted">{{ $teacher->qualification }}</small>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-primary px-2 py-1">{{ $teacher->courses_count }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }} px-2 py-1">
                                                <i class="fas fa-{{ $teacher->status === 'active' ? 'check' : 'times' }} me-1"></i>
                                                {{ ucfirst($teacher->status) }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary" title="Edit Teacher">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.teachers.toggle-status', $teacher) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-{{ $teacher->status === 'active' ? 'warning' : 'success' }}" title="{{ $teacher->status === 'active' ? 'Deactivate' : 'Activate' }} Teacher" onclick="return confirm('Are you sure you want to change this teacher status?')">
                                                        <i class="fas fa-{{ $teacher->status === 'active' ? 'user-slash' : 'user-check' }}"></i>
                                                    </button>
                                                </form>
                                                @if($teacher->courses_count == 0)
                                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="confirmDelete({{ $teacher->id }}, '{{ $teacher->name }}')" title="Delete Teacher">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-secondary disabled" title="Cannot delete teacher with courses" disabled>
                                                        <i class="fas fa-lock"></i>
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
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $teachers->firstItem() ?? 0 }} to {{ $teachers->lastItem() ?? 0 }} 
                        of {{ $teachers->total() }} results
                    </div>
                    <div>
                        {{ $teachers->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-chalkboard-teacher display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No teachers found</h4>
                    <p class="text-muted mb-4">No teachers match your search criteria.</p>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>View All Teachers
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the teacher "<strong id="teacherName"></strong>"?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. The teacher will be permanently removed from the system.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Teacher
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(teacherId, teacherName) {
    document.getElementById('teacherName').textContent = teacherName;
    document.getElementById('deleteForm').action = `/admin/teachers/${teacherId}`;
}

// Auto-submit form on filter change for better UX
document.querySelectorAll('select[name="status"], select[name="department"]').forEach(select => {
    select.addEventListener('change', function() {
        this.form.submit();
    });
});
</script>
@endpush

@push('styles')
<style>
/* Statistics cards improvements */
.border-left-primary, .border-left-success, .border-left-warning, .border-left-info {
    border-left: 0.25rem solid var(--bs-primary) !important;
}

.border-left-success {
    border-left-color: #1cc88a !important;
}

.border-left-warning {
    border-left-color: #f6c23e !important;
}

.border-left-info {
    border-left-color: #36b9cc !important;
}

/* Teacher Avatar Styling */
.teacher-avatar .avatar-placeholder {
    border: 2px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Button improvements */
.btn-group .btn {
    border-radius: 4px !important;
    margin-right: 2px;
}

/* Badge styling */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

/* Form control styling */
.form-control:focus {
    border-color: #4e73df;
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}
</style>
@endpush
