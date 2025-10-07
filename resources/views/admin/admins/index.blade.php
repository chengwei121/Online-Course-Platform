@extends('layouts.admin')

@section('title', 'Administrators Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-user-shield me-2"></i>Administrators Management
    </h1>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>Add New Administrator
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
                            Total Admins
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
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
                            Recent (30 Days)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['recent'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-filter me-2"></i>Administrators List
        </h6>
        <small class="text-muted">Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} of {{ $admins->total() }} results</small>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.admins.index') }}" class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label text-muted small">
                    <i class="fas fa-search me-1"></i>Search
                </label>
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Search by name or email..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">
                    <i class="fas fa-user-check me-1"></i>Status
                </label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </div>
        </form>

        <!-- Administrators Table -->
        @if($admins->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 25%">Administrator</th>
                            <th style="width: 25%">Email</th>
                            <th style="width: 15%">Status</th>
                            <th style="width: 15%">Join Date</th>
                            <th style="width: 15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                <td class="text-center">{{ $admin->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">{{ substr($admin->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $admin->name }}</div>
                                            @if($admin->id === auth()->id())
                                                <span class="badge bg-info">You</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $admin->email }}</div>
                                    @if($admin->email_verified_at)
                                        <small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>Verified on {{ $admin->email_verified_at->format('M d, Y') }}
                                        </small>
                                    @else
                                        <small class="text-warning">
                                            <i class="fas fa-clock me-1"></i>Not verified
                                        </small>
                                    @endif
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
                                    <div>{{ $admin->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $admin->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.admins.show', $admin) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.admins.edit', $admin) }}" 
                                           class="btn btn-warning btn-sm"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($admin->id !== auth()->id())
                                            <form action="{{ route('admin.admins.destroy', $admin) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this administrator? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-secondary btn-sm" 
                                                    title="Cannot delete yourself"
                                                    disabled>
                                                <i class="fas fa-ban"></i>
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
            <div class="d-flex justify-content-center mt-4">
                {{ $admins->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No administrators found</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'status']))
                        No administrators match your current filters.
                    @else
                        Start by adding your first administrator.
                    @endif
                </p>
                <a href="{{ route('admin.admins.create') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-1"></i>Add New Administrator
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
