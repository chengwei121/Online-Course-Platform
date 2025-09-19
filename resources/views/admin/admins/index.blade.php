@extends('layouts.admin')

@section('title', 'Administrators Management')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-shield me-2"></i>Administrators Management
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Administrators
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Verified Accounts
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['verified'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Unverified Accounts
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['unverified'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                Recent (30 days)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['recent'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Administrators Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table me-2"></i>Administrators List
                    </h6>
                </div>
                <div class="col-md-6 text-end">
                    <span class="text-muted">
                        Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} 
                        of {{ $admins->total() }} results
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <div class="mb-4">
                <form method="GET" action="{{ route('admin.admins.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label fw-semibold">
                            <i class="fas fa-search me-1"></i>Search
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   placeholder="Search by name or email..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-semibold">
                            <i class="fas fa-filter me-1"></i>Verification Status
                        </label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
                <hr class="my-4">
            </div>
            @if($admins->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Administrator</th>
                                <th width="25%">Email</th>
                                <th width="15%">Status</th>
                                <th width="15%">Created</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($admins->currentPage() - 1) * $admins->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="admin-avatar me-3">
                                                <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: bold; font-size: 14px;"
                                                     title="{{ $admin->name }}">
                                                    @php
                                                        $nameParts = explode(' ', trim($admin->name));
                                                        if (count($nameParts) >= 2) {
                                                            echo strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
                                                        } else {
                                                            echo strtoupper(substr($admin->name, 0, 2));
                                                        }
                                                    @endphp
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium text-dark">{{ $admin->name }}</div>
                                                @if($admin->id === Auth::id())
                                                    <small class="text-primary">
                                                        <i class="fas fa-crown me-1"></i>Current Admin
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $admin->email }}</span>
                                    </td>
                                    <td>
                                        @if($admin->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Unverified
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $admin->created_at->format('M d, Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $admin->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($admin->id !== Auth::id())
                                                <a href="{{ route('admin.admins.edit', $admin) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('admin.admins.toggle-verification', $admin) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-{{ $admin->email_verified_at ? 'warning' : 'success' }}" 
                                                            title="{{ $admin->email_verified_at ? 'Mark as Unverified' : 'Mark as Verified' }}">
                                                        <i class="fas fa-{{ $admin->email_verified_at ? 'times' : 'check' }}"></i>
                                                    </button>
                                                </form>
                                                
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal"
                                                        onclick="confirmDelete({{ $admin->id }}, '{{ $admin->name }}')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                <span class="badge bg-secondary">Current Admin</span>
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
                        Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} 
                        of {{ $admins->total() }} results
                    </div>
                    <div>
                        {{ $admins->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-user-shield display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No administrators found</h4>
                    <p class="text-muted mb-4">No administrators match your search criteria.</p>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>View All Administrators
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
                <p>Are you sure you want to delete the administrator "<strong id="adminName"></strong>"?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. The administrator will lose access to the system immediately.
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
                        <i class="fas fa-trash me-1"></i>Delete Administrator
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(adminId, adminName) {
    document.getElementById('adminName').textContent = adminName;
    document.getElementById('deleteForm').action = `/admin/admins/${adminId}`;
}

// Auto-submit form on filter change for better UX
document.querySelectorAll('select[name="status"]').forEach(select => {
    select.addEventListener('change', function() {
        this.form.submit();
    });
});
</script>

<style>
/* Admin Avatar Styling */
.admin-avatar .avatar-placeholder {
    border: 2px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Table row hover effects */
.table tbody tr:hover {
    background-color: #f8f9fc;
}

/* Card hover effects */
.card {
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.1) !important;
}

/* Badge styling */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

/* Button improvements */
.btn-group .btn {
    border-radius: 4px !important;
    margin-right: 2px;
}

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
</style>
@endpush