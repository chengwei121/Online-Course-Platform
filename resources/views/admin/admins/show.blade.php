@extends('layouts.admin')

@section('title', 'Administrator Details')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-shield me-2"></i>Administrator Details
        </h1>
        <div>
            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Administrator Profile Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Profile
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="admin-avatar mb-3">
                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: bold; font-size: 36px;"
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
                    
                    <h4 class="text-dark mb-1">{{ $admin->name }}</h4>
                    <p class="text-muted mb-2">{{ $admin->email }}</p>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary px-3 py-2">
                            <i class="fas fa-user-shield me-1"></i>Administrator
                        </span>
                        @if($admin->id === auth()->id())
                            <span class="badge bg-info px-3 py-2">
                                <i class="fas fa-user me-1"></i>Current User
                            </span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-center">
                        @if($admin->email_verified_at)
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>Email Verified
                            </span>
                        @else
                            <span class="badge bg-warning px-3 py-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>Email Not Verified
                            </span>
                        @endif
                    </div>

                    <hr class="my-4">

                    <div class="text-start">
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar-plus me-2"></i>Member Since
                            </small>
                            <div class="fw-bold">{{ $admin->created_at->format('F d, Y') }}</div>
                            <small class="text-muted">{{ $admin->created_at->diffForHumans() }}</small>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-clock me-2"></i>Last Updated
                            </small>
                            <div class="fw-bold">{{ $admin->updated_at->format('F d, Y') }}</div>
                            <small class="text-muted">{{ $admin->updated_at->diffForHumans() }}</small>
                        </div>

                        @if($admin->email_verified_at)
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-check-circle me-2"></i>Email Verified
                                </small>
                                <div class="fw-bold">{{ $admin->email_verified_at->format('F d, Y') }}</div>
                                <small class="text-muted">{{ $admin->email_verified_at->diffForHumans() }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Administrator
                        </a>
                        
                        @if($admin->id !== auth()->id())
                            <form action="{{ route('admin.admins.destroy', $admin) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this administrator? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Delete Administrator
                                </button>
                            </form>
                        @else
                            <button type="button" 
                                    class="btn btn-secondary" 
                                    title="Cannot delete yourself"
                                    disabled>
                                <i class="fas fa-ban me-2"></i>Cannot Delete Yourself
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Administrator Details -->
        <div class="col-xl-8 col-lg-7">
            <!-- Account Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-id-card me-2"></i>Account Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="fas fa-hashtag me-1"></i>User ID
                                </label>
                                <div class="h5 text-dark">#{{ $admin->id }}</div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="fas fa-user-tag me-1"></i>Role
                                </label>
                                <div class="h5">
                                    <span class="badge bg-primary">{{ ucfirst($admin->role) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="fas fa-user me-1"></i>Full Name
                                </label>
                                <div class="h5 text-dark">{{ $admin->name }}</div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="fas fa-envelope me-1"></i>Email Address
                                </label>
                                <div class="h5 text-dark">{{ $admin->email }}</div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="fas fa-check-circle me-1"></i>Email Verification Status
                                </label>
                                <div class="h5">
                                    @if($admin->email_verified_at)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Verified
                                        </span>
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                Verified on {{ $admin->email_verified_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Not Verified
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <label class="text-muted small mb-1">
                                    <i class="fas fa-clock me-1"></i>Account Status
                                </label>
                                <div class="h5">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Timestamps Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Activity Timestamps
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content ms-3">
                                    <h6 class="mb-1">
                                        <i class="fas fa-calendar-plus me-2 text-success"></i>Account Created
                                    </h6>
                                    <p class="text-dark mb-1">{{ $admin->created_at->format('l, F d, Y') }}</p>
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-clock me-1"></i>{{ $admin->created_at->format('g:i A') }} 
                                        ({{ $admin->created_at->diffForHumans() }})
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($admin->email_verified_at)
                            <div class="timeline-item mb-4">
                                <div class="d-flex">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content ms-3">
                                        <h6 class="mb-1">
                                            <i class="fas fa-check-circle me-2 text-info"></i>Email Verified
                                        </h6>
                                        <p class="text-dark mb-1">{{ $admin->email_verified_at->format('l, F d, Y') }}</p>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-clock me-1"></i>{{ $admin->email_verified_at->format('g:i A') }} 
                                            ({{ $admin->email_verified_at->diffForHumans() }})
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="timeline-item">
                            <div class="d-flex">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content ms-3">
                                    <h6 class="mb-1">
                                        <i class="fas fa-sync-alt me-2 text-primary"></i>Last Updated
                                    </h6>
                                    <p class="text-dark mb-1">{{ $admin->updated_at->format('l, F d, Y') }}</p>
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-clock me-1"></i>{{ $admin->updated_at->format('g:i A') }} 
                                        ({{ $admin->updated_at->diffForHumans() }})
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Statistics Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Account Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="stat-card p-3 border rounded">
                                <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                                <h6 class="text-muted small mb-1">Account Age</h6>
                                <h4 class="text-dark mb-0">{{ $admin->created_at->diffInDays(now()) }} Days</h4>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card p-3 border rounded">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h6 class="text-muted small mb-1">Verification Status</h6>
                                <h4 class="text-dark mb-0">
                                    @if($admin->email_verified_at)
                                        <span class="text-success">Verified</span>
                                    @else
                                        <span class="text-warning">Pending</span>
                                    @endif
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card p-3 border rounded">
                                <i class="fas fa-user-shield fa-2x text-info mb-2"></i>
                                <h6 class="text-muted small mb-1">Role Type</h6>
                                <h4 class="text-dark mb-0">{{ ucfirst($admin->role) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
/* Enhanced Avatar Styling */
.admin-avatar .avatar-placeholder {
    border: 4px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.admin-avatar .avatar-placeholder:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

/* Card styling */
.card {
    border: none;
    border-radius: 10px;
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* Timeline styling */
.timeline-item {
    position: relative;
}

.timeline-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.timeline-content {
    flex: 1;
}

.timeline-item:not(:last-child) .timeline-marker::after {
    content: '';
    position: absolute;
    left: 5px;
    top: 15px;
    width: 2px;
    height: calc(100% + 10px);
    background: #e3e6f0;
}

/* Stat card styling */
.stat-card {
    transition: all 0.3s ease;
    background: #f8f9fc;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    background: white;
}

/* Info item styling */
.info-item {
    padding: 10px;
    border-radius: 8px;
    background: #f8f9fc;
    transition: all 0.2s ease;
}

.info-item:hover {
    background: #e3e6f0;
}

/* Badge styling */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

/* Button styling */
.btn-warning {
    transition: all 0.2s ease;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(246, 194, 62, 0.4);
}

.btn-danger {
    transition: all 0.2s ease;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(231, 74, 59, 0.4);
}
</style>
@endpush
