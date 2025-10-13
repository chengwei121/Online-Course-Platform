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
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(44, 62, 80, 0.3);
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
        background: linear-gradient(135deg, #ecf0f1 0%, #bdc3c7 100%);
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
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
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
        color: #3498db;
    }

    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
        outline: none;
    }

    .form-control:hover {
        border-color: #cbd5e1;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        background: linear-gradient(135deg, #34495e 0%, #3498db 100%);
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
        background: #e8f4f8;
        border: 2px solid #3498db;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .password-requirements h6 {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .password-requirements ul {
        margin: 0;
        padding-left: 1.5rem;
        color: #2c3e50;
    }

    .password-requirements li {
        margin-bottom: 0.25rem;
    }

    .info-card {
        background: #e8f4f8;
        border-left: 4px solid #3498db;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .info-card i {
        color: #3498db;
        margin-right: 0.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-box {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
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

    <!-- Current Profile Summary -->
    <div class="profile-card" style="background: linear-gradient(135deg, #e8f4f8 0%, #d4e9f2 100%); border-left: 4px solid #3498db;">
        <h3><i class="fas fa-info-circle"></i> Your Current Profile</h3>
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2"><strong><i class="fas fa-user text-primary"></i> Name:</strong> {{ $teacher->name }}</p>
                <p class="mb-2"><strong><i class="fas fa-envelope text-primary"></i> Email:</strong> {{ $user->email }}</p>
                <p class="mb-2"><strong><i class="fas fa-phone text-primary"></i> Phone:</strong> {{ $teacher->phone ?: 'Not set' }}</p>
                <p class="mb-2"><strong><i class="fas fa-graduation-cap text-primary"></i> Qualification:</strong> {{ $teacher->qualification ?: 'Not set' }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong><i class="fas fa-building text-primary"></i> Department:</strong> {{ $teacher->department ?: 'Not set' }}</p>
                <p class="mb-2"><strong><i class="fas fa-money-bill-wave text-primary"></i> Hourly Rate:</strong> RM {{ number_format($teacher->hourly_rate ?? 0, 2) }} <span class="badge bg-info text-white">Admin Set</span></p>
                <p class="mb-2"><strong><i class="fas fa-calendar text-primary"></i> Account Created:</strong> {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</p>
                <p class="mb-2"><strong><i class="fas fa-clock text-primary"></i> Last Updated:</strong> {{ $teacher->updated_at ? $teacher->updated_at->format('d M Y, g:i A') : 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Profile Information Card -->
    <div class="profile-card">
        <h3><i class="fas fa-user-edit"></i> Update Profile Information</h3>

        <!-- Profile Picture Section -->
        <div class="profile-picture-section">
            <h5 class="mb-3" style="color: #2c3e50; font-weight: 600;">
                <i class="fas fa-camera"></i> Current Profile Picture
            </h5>
            
            {{-- Display current profile picture or placeholder --}}
            @if($teacher->profile_picture)
                <img src="{{ asset('storage/' . $teacher->profile_picture) }}" 
                     alt="Profile Picture" 
                     class="profile-picture-preview"
                     id="profilePicturePreview"
                     onerror="console.error('Image failed to load:', this.src); this.style.display='none'; document.getElementById('profilePicturePlaceholder').style.display='flex';">
                <div class="profile-picture-placeholder" id="profilePicturePlaceholder" style="display: none;">
                    {{ strtoupper(substr($teacher->name, 0, 2)) }}
                </div>
            @else
                <img src="" 
                     alt="Profile Picture" 
                     class="profile-picture-preview"
                     id="profilePicturePreview"
                     style="display: none;">
                <div class="profile-picture-placeholder" id="profilePicturePlaceholder">
                    {{ strtoupper(substr($teacher->name, 0, 2)) }}
                </div>
            @endif
            
            <div class="mt-3">
                @if($teacher->profile_picture)
                    <p class="text-muted mb-2">
                        <i class="fas fa-check-circle text-success"></i> Picture uploaded
                        <br>
                        <small class="text-info">
                            <i class="fas fa-image"></i> Path: {{ $teacher->profile_picture }}
                            <br>
                            <i class="fas fa-link"></i> URL: {{ asset('storage/' . $teacher->profile_picture) }}
                        </small>
                    </p>
                    <form action="{{ route('teacher.profile.remove-picture') }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove profile picture?')">
                            <i class="fas fa-trash"></i> Remove Picture
                        </button>
                    </form>
                @else
                    <p class="text-muted mb-2">
                        <i class="fas fa-info-circle"></i> No picture uploaded yet
                    </p>
                @endif
            </div>
        </div>

        <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Full Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i> Full Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $teacher->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Email Address -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
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

                <!-- Phone Number (Malaysia) -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone"></i> Phone Number (Malaysia) <span class="text-danger">*</span>
                        </label>
                        @if($teacher->phone)
                            <div class="mb-1">
                                <small class="text-success"><i class="fas fa-check-circle"></i> Current: <strong>+60 {{ $teacher->phone }}</strong></small>
                            </div>
                        @endif
                        <div class="input-group">
                            <span class="input-group-text">🇲🇾 +60</span>
                            <input type="text" 
                                   name="phone" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone"
                                   value="{{ old('phone', $teacher->phone) }}"
                                   placeholder="12-345-6789"
                                   pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}"
                                   title="Please enter a valid Malaysian phone number (e.g., 12-345-6789)"
                                   required>
                        </div>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: XX-XXX-XXXX (without +60 prefix)</small>
                    </div>
                </div>

                <!-- Qualification -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-graduation-cap"></i> Qualification <span class="text-danger">*</span>
                        </label>
                        @if($teacher->qualification)
                            <div class="mb-1">
                                <small class="text-success"><i class="fas fa-check-circle"></i> Current: <strong>{{ $teacher->qualification }}</strong></small>
                            </div>
                        @endif
                        <select name="qualification" 
                                class="form-control @error('qualification') is-invalid @enderror" 
                                required>
                            <option value="">Select Qualification</option>
                            <option value="Certificate" {{ old('qualification', $teacher->qualification) === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                            <option value="Diploma" {{ old('qualification', $teacher->qualification) === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                            <option value="Bachelor's Degree" {{ old('qualification', $teacher->qualification) === "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                            <option value="Master's Degree" {{ old('qualification', $teacher->qualification) === "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                            <option value="PhD/Doctorate" {{ old('qualification', $teacher->qualification) === 'PhD/Doctorate' ? 'selected' : '' }}>PhD/Doctorate</option>
                            <option value="Professional Certification" {{ old('qualification', $teacher->qualification) === 'Professional Certification' ? 'selected' : '' }}>Professional Certification</option>
                        </select>
                        @error('qualification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Department -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-building"></i> Department <span class="text-danger">*</span>
                        </label>
                        @if($teacher->department)
                            <div class="mb-1">
                                <small class="text-success"><i class="fas fa-check-circle"></i> Current: <strong>{{ $teacher->department }}</strong></small>
                            </div>
                        @endif
                        <select name="department" 
                                class="form-control @error('department') is-invalid @enderror" 
                                required>
                            <option value="">Select Department</option>
                            <option value="Information Technology" {{ old('department', $teacher->department) === 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                            <option value="Computer Science" {{ old('department', $teacher->department) === 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                            <option value="Software Engineering" {{ old('department', $teacher->department) === 'Software Engineering' ? 'selected' : '' }}>Software Engineering</option>
                            <option value="Information Systems" {{ old('department', $teacher->department) === 'Information Systems' ? 'selected' : '' }}>Information Systems</option>
                            <option value="Computer Engineering" {{ old('department', $teacher->department) === 'Computer Engineering' ? 'selected' : '' }}>Computer Engineering</option>
                            <option value="Cybersecurity" {{ old('department', $teacher->department) === 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                            <option value="Data Science" {{ old('department', $teacher->department) === 'Data Science' ? 'selected' : '' }}>Data Science</option>
                            <option value="Artificial Intelligence" {{ old('department', $teacher->department) === 'Artificial Intelligence' ? 'selected' : '' }}>Artificial Intelligence</option>
                        </select>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Hourly Rate (Read-Only) -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-money-bill-wave"></i> Hourly Rate (Set by Admin)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">RM</span>
                            <input type="text" 
                                   class="form-control bg-light" 
                                   value="{{ number_format($teacher->hourly_rate ?? 0, 2) }}"
                                   readonly
                                   style="cursor: not-allowed;">
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-lock"></i> Only administrators can modify this rate
                        </small>
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

        <!-- Current Password Display -->
        <div class="alert alert-info mb-3" style="background: #e8f4f8; border-left: 4px solid #3498db;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        <strong><i class="fas fa-shield-alt"></i> Current Password:</strong>
                        <span class="ms-2" style="letter-spacing: 3px; font-family: monospace;">••••••••</span>
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> Last changed: {{ $user->updated_at ? $user->updated_at->format('d M Y') : 'N/A' }}
                    </small>
                </div>
            </div>
        </div>

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
                            <i class="fas fa-key"></i> Enter Current Password to Verify
                        </label>
                        <input type="password" 
                               name="current_password" 
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="Enter your current password"
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">You must enter your current password to make changes</small>
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
// Phone number formatting for Malaysian format
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) value = value.substring(0, 2) + '-' + value.substring(2);
            if (value.length >= 6) value = value.substring(0, 6) + '-' + value.substring(6, 10);
            e.target.value = value;
        });
    }
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            let preview = document.getElementById('profilePicturePreview');
            const placeholder = document.getElementById('profilePicturePlaceholder');
            
            if (preview) {
                // Update existing image
                preview.src = e.target.result;
                preview.style.display = 'block';
            } else if (placeholder) {
                // Replace placeholder with image
                placeholder.style.display = 'none';
                placeholder.insertAdjacentHTML('afterend', 
                    `<img src="${e.target.result}" 
                          alt="Profile Picture" 
                          class="profile-picture-preview" 
                          id="profilePicturePreview" 
                          style="display: block;">`
                );
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
