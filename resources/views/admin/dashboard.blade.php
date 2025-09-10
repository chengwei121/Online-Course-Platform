@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header')
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>
        Dashboard
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <span class="text-muted">Welcome back, {{ Auth::user()->name }}!</span>
        </div>
    </div>
@endsection

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Teachers</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['total_teachers'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard-teacher fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Active Teachers</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['active_teachers'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Courses</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['total_courses'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Students</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['total_students'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Recent Teachers -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Recent Teachers
                </h6>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if($recentTeachers->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentTeachers as $teacher)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($teacher->profile_picture)
                                            <img src="{{ Storage::url($teacher->profile_picture) }}" 
                                                 class="rounded-circle" width="40" height="40" alt="Profile">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $teacher->name }}</h6>
                                        <small class="text-muted">{{ $teacher->department }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No teachers registered yet.</p>
                        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add First Teacher
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-book me-2"></i>
                    Recent Courses
                </h6>
                <a href="#" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if($recentCourses->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentCourses as $course)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($course->thumbnail)
                                            <img src="{{ Storage::url($course->thumbnail) }}" 
                                                 class="rounded" width="50" height="40" alt="Course thumbnail">
                                        @else
                                            <div class="bg-primary rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 40px;">
                                                <i class="fas fa-book text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ Str::limit($course->title, 30) }}</h6>
                                        <small class="text-muted">by {{ $course->instructor->name ?? 'Unknown' }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'warning' }}">
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

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.teachers.create') }}" class="btn btn-success w-100 py-3">
                            <i class="fas fa-user-plus fa-2x mb-2 d-block"></i>
                            Add New Teacher
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-chalkboard-teacher fa-2x mb-2 d-block"></i>
                            Manage Teachers
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-warning w-100 py-3">
                            <i class="fas fa-book fa-2x mb-2 d-block"></i>
                            Manage Courses
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-info w-100 py-3">
                            <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                            View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
