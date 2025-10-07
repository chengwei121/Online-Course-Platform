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
                        @if($teacher->profile_picture)
                            <img src="{{ Storage::url($teacher->profile_picture) }}" 
                                 class="rounded-circle img-fluid mb-3" width="200" height="200" alt="Profile Picture">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                 style="width: 200px; height: 200px;">
                                <i class="fas fa-user text-white fa-4x"></i>
                            </div>
                        @endif
                        
                        <h4 class="mb-2">{{ $teacher->name }}</h4>
                        <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }} fs-6">
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
