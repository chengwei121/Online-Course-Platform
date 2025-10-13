@extends('teacher.layouts.app')

@section('title', 'My Profile')

@push('styles')
<style>
    .profile-page {
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .page-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .profile-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .profile-picture-section {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .profile-picture-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        margin: 0 auto 1.5rem;
        display: block;
    }

    .profile-picture-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        font-weight: 700;
        border: 5px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        margin: 0 auto 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-label i {
        margin-right: 0.5rem;
        color: #667eea;
    }

    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-control:hover {
        border-color: #cbd5e1;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    .btn-outline-secondary {
        border: 2px solid #e5e7eb;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        color: #6b7280;
        background: white;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #374151;
    }

    .alert {
        border-radius: 10px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        border: none;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .password-requirements {
        background: #f0f9ff;
        border: 2px solid #bfdbfe;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .password-requirements h6 {
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 0.5rem;
    }

    .password-requirements ul {
        margin: 0;
        padding-left: 1.5rem;
        color: #3b82f6;
    }

    .password-requirements li {
        margin-bottom: 0.25rem;
    }

    .info-card {
        background: #f0f9ff;
        border-left: 4px solid #3b82f6;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .info-card i {
        color: #3b82f6;
        margin-right: 0.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
    }

    .stat-box h4 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .stat-box p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<div class="profile-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-user-circle"></i> My Profile</h1>
        <p>Manage your profile information and account settings</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Profile Information Card -->
    <div class="profile-card">
        <h3><i class="fas fa-user-edit"></i> Profile Information</h3>

        <!-- Profile Picture Section -->
        <div class="profile-picture-section">
            @if($teacher->profile_picture)
                <img src="{{ asset('storage/' . $teacher->profile_picture) }}" 
                     alt="Profile Picture" 
                     class="profile-picture-preview"
                     id="profilePicturePreview">
            @else
                <div class="profile-picture-placeholder" id="profilePicturePlaceholder">
                    {{ strtoupper(substr($teacher->name, 0, 2)) }}
                </div>
            @endif
            
            <div class="mt-3">
                @if($teacher->profile_picture)
                    <form action="{{ route('teacher.profile.remove-picture') }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove profile picture?')">
                            <i class="fas fa-trash"></i> Remove Picture
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- User Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i> Account Name (Login Name)
                        </label>
                        <input type="text" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Teacher Display Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-chalkboard-teacher"></i> Teacher Display Name
                        </label>
                        <input type="text" 
                               name="teacher_name" 
                               class="form-control @error('teacher_name') is-invalid @enderror" 
                               value="{{ old('teacher_name', $teacher->name) }}"
                               required>
                        @error('teacher_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Phone -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone"></i> Phone Number
                        </label>
                        <input type="text" 
                               name="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $teacher->phone) }}"
                               placeholder="+60 12-345 6789">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Qualification -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-graduation-cap"></i> Qualification
                        </label>
                        <input type="text" 
                               name="qualification" 
                               class="form-control @error('qualification') is-invalid @enderror" 
                               value="{{ old('qualification', $teacher->qualification) }}"
                               placeholder="e.g., PhD in Computer Science">
                        @error('qualification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Specialization -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-star"></i> Specialization
                        </label>
                        <input type="text" 
                               name="specialization" 
                               class="form-control @error('specialization') is-invalid @enderror" 
                               value="{{ old('specialization', $teacher->specialization) }}"
                               placeholder="e.g., Web Development, AI">
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Experience Years -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-clock"></i> Years of Experience
                        </label>
                        <input type="number" 
                               name="experience_years" 
                               class="form-control @error('experience_years') is-invalid @enderror" 
                               value="{{ old('experience_years', $teacher->experience_years) }}"
                               min="0"
                               max="50"
                               placeholder="e.g., 5">
                        @error('experience_years')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Profile Picture Upload -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-image"></i> Profile Picture
                        </label>
                        <input type="file" 
                               name="profile_picture" 
                               class="form-control @error('profile_picture') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg,image/gif"
                               onchange="previewImage(this)">
                        <small class="text-muted">Max 2MB (JPEG, PNG, JPG, GIF)</small>
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Bio -->
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left"></i> Bio / About Me
                        </label>
                        <textarea name="bio" 
                                  rows="4" 
                                  class="form-control @error('bio') is-invalid @enderror"
                                  placeholder="Tell students about yourself...">{{ old('bio', $teacher->bio) }}</textarea>
                        <small class="text-muted">Maximum 1000 characters</small>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password Card -->
    <div class="profile-card">
        <h3><i class="fas fa-lock"></i> Change Password</h3>

        <div class="info-card">
            <i class="fas fa-info-circle"></i>
            <strong>Important:</strong> You will remain logged in after changing your password.
        </div>

        <form action="{{ route('teacher.profile.update-password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Current Password -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-key"></i> Current Password
                        </label>
                        <input type="password" 
                               name="current_password" 
                               class="form-control @error('current_password') is-invalid @enderror"
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- New Password -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> New Password
                        </label>
                        <input type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> Confirm New Password
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="form-control"
                               required>
                    </div>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="password-requirements">
                <h6><i class="fas fa-shield-alt"></i> Password Requirements:</h6>
                <ul>
                    <li>Minimum 8 characters</li>
                    <li>At least one uppercase letter (A-Z)</li>
                    <li>At least one lowercase letter (a-z)</li>
                    <li>At least one number (0-9)</li>
                    <li>At least one symbol (!@#$%^&*)</li>
                </ul>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-shield-alt"></i> Update Password
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('profilePicturePreview');
            const placeholder = document.getElementById('profilePicturePlaceholder');
            
            if (preview) {
                preview.src = e.target.result;
            } else if (placeholder) {
                // Replace placeholder with image
                placeholder.outerHTML = `<img src="${e.target.result}" alt="Profile Picture" class="profile-picture-preview" id="profilePicturePreview">`;
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
@endpush
@endsection
