@extends('layouts.admin')

@section('title', 'Teacher Details')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-user me-2"></i>Teacher Details
    </h1>
    <div class="btn-group">
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Teachers
        </a>
        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Edit Teacher
        </a>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-id-card me-2"></i>
                    Teacher Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="profile-image-container">
                            @if($teacher->profile_picture)
                                <div class="profile-image-wrapper">
                                    <img src="{{ Storage::url($teacher->profile_picture) }}" 
                                         class="profile-image" alt="{{ $teacher->name }}">
                                    <div class="profile-image-border"></div>
                                </div>
                            @else
                                <div class="profile-image-wrapper">
                                    <div class="profile-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="profile-image-border"></div>
                                </div>
                            @endif
                            
                            <!-- Status Badge Overlay -->
                            <div class="profile-status-badge {{ $teacher->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                <i class="fas fa-{{ $teacher->status === 'active' ? 'check-circle' : 'pause-circle' }}"></i>
                            </div>
                        </div>
                        
                        <h4 class="mb-2 mt-3 teacher-name">{{ $teacher->name }}</h4>
                        <p class="text-muted mb-2">{{ $teacher->department }}</p>
                        <span class="badge badge-status bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($teacher->status) }}
                        </span>
                    </div>
                    
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" width="30%"><i class="fas fa-envelope me-2 text-primary"></i>Email:</td>
                                    <td>{{ $teacher->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="fas fa-phone me-2 text-primary"></i>Phone:</td>
                                    <td>{{ $teacher->phone ?: 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="fas fa-graduation-cap me-2 text-primary"></i>Qualification:</td>
                                    <td>{{ $teacher->qualification }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="fas fa-building me-2 text-primary"></i>Department:</td>
                                    <td>{{ $teacher->department }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="fas fa-coins me-2 text-primary"></i>Hourly Rate:</td>
                                    <td>RM{{ number_format($teacher->hourly_rate ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="fas fa-calendar me-2 text-primary"></i>Joined:</td>
                                    <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="fas fa-clock me-2 text-primary"></i>Last Updated:</td>
                                    <td>{{ $teacher->updated_at->format('M d, Y \a\t g:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($teacher->bio)
                    <hr>
                    <div class="mt-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user-edit me-2"></i>Biography
                        </h6>
                        <p class="text-muted">{{ $teacher->bio }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Course Statistics -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <div class="mb-3">
                            <i class="fas fa-book fa-2x text-primary mb-2"></i>
                            <h4 class="mb-0">{{ $teacher->courses->count() }}</h4>
                            <small class="text-muted">Total Courses</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <i class="fas fa-users fa-2x text-success mb-2"></i>
                            <h4 class="mb-0">{{ $teacher->courses->sum(function($course) { return $course->enrollments_count ?? 0; }) }}</h4>
                            <small class="text-muted">Total Students</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Courses -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-book me-2"></i>
                    Courses ({{ $teacher->courses->count() }})
                </h6>
            </div>
            <div class="card-body">
                @if($teacher->courses->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($teacher->courses as $course)
                            <div class="list-group-item d-flex justify-content-between align-items-start px-0">
                                <div class="me-auto">
                                    <div class="fw-bold">{{ Str::limit($course->title, 40) }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>{{ $course->enrollments_count ?? 0 }} students
                                    </small>
                                </div>
                                <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'warning' }} rounded-pill">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No courses created yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Profile Image Container */
.profile-image-container {
    position: relative;
    width: 220px;
    height: 220px;
    margin: 0 auto 20px;
}

/* Profile Image Wrapper - Modern Rounded Square with Soft Corners */
.profile-image-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    border-radius: 30px; /* Soft rounded square */
    overflow: hidden;
    background: linear-gradient(135deg, #505050ff 0%, #d4d4d4ff 100%);
    padding: 5px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.profile-image-wrapper:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(102, 126, 234, 0.4);
}

/* Actual Profile Image */
.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 26px;
    background: white;
}

/* Profile Placeholder for No Image */
.profile-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 26px;
}

.profile-placeholder i {
    font-size: 80px;
    color: white;
    opacity: 0.9;
}

/* Decorative Border Animation */
.profile-image-border {
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    border-radius: 32px;
    background: linear-gradient(45deg, #667eea, #764ba2, #f093fb, #4facfe);
    background-size: 400% 400%;
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease;
    animation: gradientShift 8s ease infinite;
}

.profile-image-wrapper:hover .profile-image-border {
    opacity: 1;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Status Badge Overlay */
.profile-status-badge {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 10;
    transition: all 0.3s ease;
}

.profile-status-badge:hover {
    transform: scale(1.1);
}

.profile-status-badge i {
    font-size: 18px;
    color: white;
}

.profile-status-badge.status-active {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.profile-status-badge.status-inactive {
    background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%);
}

/* Teacher Name Styling */
.teacher-name {
    font-weight: 700;
    color: #2d3748;
    font-size: 1.5rem;
    letter-spacing: -0.5px;
}

/* Badge Status */
.badge-status {
    padding: 8px 20px;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Alternative Shape Options - Uncomment to use */

/* Option 1: Perfect Circle (Default rounded-circle) */
/*
.profile-image-wrapper {
    border-radius: 50%;
}
.profile-image {
    border-radius: 50%;
}
.profile-placeholder {
    border-radius: 50%;
}
.profile-image-border {
    border-radius: 50%;
}
*/

/* Option 2: Hexagon Shape */
/*
.profile-image-wrapper {
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    border-radius: 0;
}
.profile-image {
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    border-radius: 0;
}
.profile-placeholder {
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    border-radius: 0;
}
*/

/* Option 3: Octagon Shape */
/*
.profile-image-wrapper {
    clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%);
    border-radius: 0;
}
.profile-image {
    clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%);
    border-radius: 0;
}
.profile-placeholder {
    clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%);
    border-radius: 0;
}
*/

/* Option 4: Shield/Badge Shape */
/*
.profile-image-wrapper {
    clip-path: polygon(50% 0%, 100% 20%, 100% 80%, 50% 100%, 0% 80%, 0% 20%);
    border-radius: 0;
}
.profile-image {
    clip-path: polygon(50% 0%, 100% 20%, 100% 80%, 50% 100%, 0% 80%, 0% 20%);
    border-radius: 0;
}
.profile-placeholder {
    clip-path: polygon(50% 0%, 100% 20%, 100% 80%, 50% 100%, 0% 80%, 0% 20%);
    border-radius: 0;
}
*/

/* Option 5: Star Shape */
/*
.profile-image-wrapper {
    clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
    border-radius: 0;
}
.profile-image {
    clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
    border-radius: 0;
}
.profile-placeholder {
    clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
    border-radius: 0;
}
*/

/* Option 6: Diamond Shape */
/*
.profile-image-wrapper {
    clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
    border-radius: 0;
}
.profile-image {
    clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
    border-radius: 0;
}
.profile-placeholder {
    clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
    border-radius: 0;
}
*/

/* Option 7: Blob/Organic Shape */
/*
.profile-image-wrapper {
    border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
}
.profile-image {
    border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
}
.profile-placeholder {
    border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
}
*/

/* Responsive Adjustments */
@media (max-width: 768px) {
    .profile-image-container {
        width: 180px;
        height: 180px;
    }
    
    .teacher-name {
        font-size: 1.25rem;
    }
    
    .profile-placeholder i {
        font-size: 60px;
    }
}

@media (max-width: 576px) {
    .profile-image-container {
        width: 150px;
        height: 150px;
    }
    
    .profile-status-badge {
        width: 36px;
        height: 36px;
        bottom: 5px;
        right: 5px;
    }
    
    .profile-status-badge i {
        font-size: 16px;
    }
}
</style>
@endpush
