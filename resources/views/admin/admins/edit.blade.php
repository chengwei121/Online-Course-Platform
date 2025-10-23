@extends('layouts.admin')

@section('title', 'Edit Administrator')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-edit me-2"></i>Edit Administrator
        </h1>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Administrators
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <!-- Administrator Profile Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-user-shield me-2"></i>Administrator Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <div class="admin-avatar mx-auto mb-3">
                                <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: bold; font-size: 24px;"
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
                            <div class="text-center">
                                <h5 class="text-dark mb-1">{{ $admin->name }}</h5>
                                <span class="badge bg-primary">Administrator</span>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <form action="{{ route('admin.admins.update', $admin) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user me-1"></i>Full Name
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $admin->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-1"></i>Email Address
                                        </label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $admin->email) }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="text-dark mb-3">
                                    <i class="fas fa-lock me-2"></i>Password Settings
                                </h6>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Leave password fields empty if you don't want to change the password.
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-key me-1"></i>New Password
                                        </label>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               autocomplete="new-password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">
                                            <i class="fas fa-key me-1"></i>Confirm Password
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               autocomplete="new-password">
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="text-dark mb-3">
                                    <i class="fas fa-cog me-2"></i>Account Settings
                                </h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   role="switch" 
                                                   id="email_verified_at" 
                                                   name="email_verified_at" 
                                                   value="1"
                                                   {{ $admin->email_verified_at ? 'checked' : '' }}>
                                            <label class="form-check-label" for="email_verified_at">
                                                <i class="fas fa-check-circle me-1"></i>Email Verified
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Toggle email verification status for this administrator.
                                        </small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                            <div>
                                                <small class="text-muted">Account Created:</small><br>
                                                <strong>{{ $admin->created_at->format('M d, Y g:i A') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div>
                                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i>Cancel
                                        </a>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>Update Administrator
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-info-circle me-2"></i>Account Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong class="text-dark">Account Status:</strong>
                                @if($admin->email_verified_at)
                                    <span class="badge bg-success ms-2">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning ms-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Unverified
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <strong class="text-dark">Role:</strong>
                                <span class="badge bg-primary ms-2">{{ ucfirst($admin->role) }}</span>
                            </div>
                            <div class="mb-3">
                                <strong class="text-dark">User ID:</strong>
                                <span class="text-muted ms-2">#{{ $admin->id }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong class="text-dark">Created:</strong>
                                <span class="text-muted ms-2">{{ $admin->created_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <div class="mb-3">
                                <strong class="text-dark">Last Updated:</strong>
                                <span class="text-muted ms-2">{{ $admin->updated_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <div class="mb-3">
                                <strong class="text-dark">Account Age:</strong>
                                <span class="text-muted ms-2">{{ $admin->created_at->diffForHumans() }}</span>
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
<script>
// Show/hide password confirmation field based on password input
document.getElementById('password').addEventListener('input', function() {
    const confirmField = document.getElementById('password_confirmation');
    const confirmLabel = confirmField.previousElementSibling;
    
    if (this.value.length > 0) {
        confirmField.required = true;
        confirmLabel.innerHTML = '<i class="fas fa-key me-1"></i>Confirm Password <span class="text-danger">*</span>';
    } else {
        confirmField.required = false;
        confirmLabel.innerHTML = '<i class="fas fa-key me-1"></i>Confirm Password';
        confirmField.value = '';
    }
});

// Form validation feedback
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    
    if (password && password !== passwordConfirm) {
        e.preventDefault();
        const confirmField = document.getElementById('password_confirmation');
        confirmField.classList.add('is-invalid');
        
        // Remove existing feedback
        const existingFeedback = confirmField.parentNode.querySelector('.invalid-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
        
        // Add new feedback
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = 'Password confirmation does not match.';
        confirmField.parentNode.appendChild(feedback);
        
        // Remove error styling after user starts typing
        confirmField.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const feedback = this.parentNode.querySelector('.invalid-feedback');
            if (feedback) feedback.remove();
        }, { once: true });
    }
});
</script>

<style>
/* Enhanced Avatar Styling */
.admin-avatar .avatar-placeholder {
    border: 3px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.admin-avatar .avatar-placeholder:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

/* Card styling improvements */
.card {
    transition: box-shadow 0.2s ease;
    border: none;
    border-radius: 10px;
}

.card:hover {
    box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.1) !important;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    background-color: #4a5568;
    color: white;
}

/* Form improvements */
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* Badge styling */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

/* Alert styling */
.alert-info {
    border-left: 4px solid #36b9cc;
    background-color: rgba(54, 185, 204, 0.1);
    border-color: rgba(54, 185, 204, 0.2);
}

/* Switch styling */
.form-check-input:checked {
    background-color: #1cc88a;
    border-color: #1cc88a;
}
</style>
@endpush