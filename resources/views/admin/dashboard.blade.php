@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
        <div class="flex-grow-1">
            <h1 class="h2 mb-2 mb-lg-0">
                <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                Dashboard
            </h1>
            <p class="text-muted mb-0 fs-6">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
        </div>
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
            <div class="text-center text-sm-end">
                <small class="text-muted d-block">Last Login</small>
                <small class="fw-bold">{{ Auth::user()->updated_at ? Auth::user()->updated_at->diffForHumans() : 'Never' }}</small>
            </div>
            <div class="badge bg-success fs-6 px-3 py-2">
                <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                System Online
            </div>
        </div>
    </div>
@endsection

@section('content')
<div data-page-loaded="true">
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!-- Statistics Cards -->
<div class="row g-3 g-lg-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 hover-card">
            <div class="card-body p-3 p-lg-4 position-relative overflow-hidden">
                <div class="row align-items-center g-0">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-uppercase text-muted mb-1 mb-lg-2">Total Teachers</div>
                        <div class="h4 h3-lg mb-0 font-weight-bold text-dark total-teachers">{{ $stats['total_teachers'] }}</div>
                        <div class="mt-2 d-none d-sm-block">
                            <small class="text-{{ $growthTrends['teachers'] >= 0 ? 'success' : 'danger' }}">
                                <i class="fas fa-arrow-{{ $growthTrends['teachers'] >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ $growthTrends['teachers'] >= 0 ? '+' : '' }}{{ $growthTrends['teachers'] }}% from last month
                            </small>
                            <div class="mt-1">
                                <span class="badge bg-primary realtime-indicator">
                                    <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i>
                                    Live
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-shape bg-gradient-primary text-white rounded-circle shadow d-flex align-items-center justify-content-center">
                            <i class="fas fa-chalkboard-teacher fs-5 fs-4-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3 d-none d-md-block" style="height: 4px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 hover-card">
            <div class="card-body p-3 p-lg-4 position-relative overflow-hidden">
                <div class="row align-items-center g-0">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-uppercase text-muted mb-1 mb-lg-2">Active Teachers</div>
                        <div class="h4 h3-lg mb-0 font-weight-bold text-dark active-teachers">{{ $stats['active_teachers'] }}</div>
                        <div class="mt-2 d-none d-sm-block">
                            <small class="text-{{ $growthTrends['teachers'] >= 0 ? 'success' : 'danger' }}">
                                <i class="fas fa-arrow-{{ $growthTrends['teachers'] >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ $growthTrends['teachers'] >= 0 ? '+' : '' }}{{ $growthTrends['teachers'] }}% active rate
                            </small>
                            <div class="mt-1">
                                <span class="badge bg-success realtime-indicator">
                                    <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i>
                                    Live
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-shape bg-gradient-success text-white rounded-circle shadow d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-check fs-5 fs-4-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3 d-none d-md-block" style="height: 4px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 hover-card">
            <div class="card-body p-3 p-lg-4 position-relative overflow-hidden">
                <div class="row align-items-center g-0">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-uppercase text-muted mb-1 mb-lg-2">Total Courses</div>
                        <div class="h4 h3-lg mb-0 font-weight-bold text-dark total-courses">{{ $stats['total_courses'] }}</div>
                        <div class="mt-2 d-none d-sm-block">
                            <small class="text-{{ $growthTrends['courses'] >= 0 ? 'success' : ($growthTrends['courses'] == 0 ? 'warning' : 'danger') }}">
                                <i class="fas fa-arrow-{{ $growthTrends['courses'] > 0 ? 'up' : ($growthTrends['courses'] == 0 ? 'right' : 'down') }} me-1"></i>
                                {{ $growthTrends['courses'] >= 0 ? '+' : '' }}{{ $growthTrends['courses'] }}% from last month
                            </small>
                            <div class="mt-1">
                                <span class="badge bg-warning realtime-indicator">
                                    <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i>
                                    Live
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-shape bg-gradient-warning text-white rounded-circle shadow d-flex align-items-center justify-content-center">
                            <i class="fas fa-book fs-5 fs-4-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3 d-none d-md-block" style="height: 4px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 hover-card">
            <div class="card-body p-3 p-lg-4 position-relative overflow-hidden">
                <div class="row align-items-center g-0">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-uppercase text-muted mb-1 mb-lg-2">Total Students</div>
                        <div class="h4 h3-lg mb-0 font-weight-bold text-dark total-students">{{ $stats['total_students'] }}</div>
                        <div class="mt-2 d-none d-sm-block">
                            <small class="text-{{ $growthTrends['students'] >= 0 ? 'success' : 'danger' }}">
                                <i class="fas fa-arrow-{{ $growthTrends['students'] >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ $growthTrends['students'] >= 0 ? '+' : '' }}{{ $growthTrends['students'] }}% from last month
                            </small>
                            <div class="mt-1">
                                <span class="badge bg-info realtime-indicator">
                                    <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i>
                                    Live
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-shape bg-gradient-info text-white rounded-circle shadow d-flex align-items-center justify-content-center">
                            <i class="fas fa-users fs-5 fs-4-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3 d-none d-md-block" style="height: 4px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 90%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Row -->
<div class="row g-3 g-lg-4 mb-4">
    <!-- System Overview -->
    <div class="col-12 col-xl-8 mb-3 mb-xl-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 py-3" style="background-color: #1a202c;">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-area me-2"></i>
                        Platform Overview
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" id="periodDropdown">
                            <i class="fas fa-calendar me-1"></i>
                            <span class="d-none d-sm-inline" id="periodText">This Month</span>
                            <span class="d-sm-none" id="periodTextShort">Month</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item period-option" href="#" data-period="week">This Week</a></li>
                            <li><a class="dropdown-item period-option active" href="#" data-period="month">This Month</a></li>
                            <li><a class="dropdown-item period-option" href="#" data-period="year">This Year</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-2 g-md-0">
                    <div class="col-12 col-md-4 text-center mb-3">
                        <div class="border-end-md h-100 d-flex flex-column justify-content-center">
                            <div class="h4 h3-lg text-primary mb-1">{{ $stats['total_courses'] }}</div>
                            <small class="text-muted">Total Courses</small>
                            <div class="mt-1">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    {{ $stats['published_courses'] }} published
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 text-center mb-3">
                        <div class="border-end-md h-100 d-flex flex-column justify-content-center">
                            <div class="h4 h3-lg text-success mb-1">{{ round(($stats['active_teachers'] / max($stats['total_teachers'], 1)) * 100) }}%</div>
                            <small class="text-muted">Active Teachers</small>
                            <div class="mt-1">
                                <small class="text-info">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $stats['active_teachers'] }}/{{ $stats['total_teachers'] }} active
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 text-center mb-3">
                        <div class="h-100 d-flex flex-column justify-content-center">
                            <div class="h4 h3-lg text-info mb-1">{{ $stats['total_enrollments'] }}</div>
                            <small class="text-muted">Total Enrollments</small>
                            <div class="mt-1">
                                <small class="text-warning">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    {{ $stats['total_students'] }} students
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 d-none d-lg-block">
                    <canvas id="platformChart" height="100"></canvas>
                </div>
                <div class="mt-3 d-lg-none">
                    <canvas id="platformChart" height="200"></canvas>
                </div>
                
                <!-- Real-time data indicators -->
                <div class="row mt-3 pt-3 border-top">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-sync-alt me-1"></i>
                                Last updated: {{ now()->format('M d, Y H:i:s') }}
                            </small>
                            <div class="d-flex gap-3">
                                <small class="text-success">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                    Live Data
                                </small>
                                <small class="text-primary">
                                    <i class="fas fa-chart-line me-1"></i>
                                    <span id="chartViewText">6 Months View</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 py-3" style="background-color: #1a202c;">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-server me-2"></i>
                    System Status
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background-color: #f8f9fa;">
                    <span class="text-muted">Database</span>
                    <span class="badge bg-success">
                        <i class="fas fa-check me-1"></i>Online
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background-color: #f8f9fa;">
                    <span class="text-muted">File Storage</span>
                    <span class="badge bg-success">
                        <i class="fas fa-check me-1"></i>Online
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background-color: #f8f9fa;">
                    <span class="text-muted">Email Service</span>
                    <span class="badge bg-success">
                        <i class="fas fa-check me-1"></i>Online
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background-color: #f8f9fa;">
                    <span class="text-muted">Backup Status</span>
                    <span class="badge bg-warning">
                        <i class="fas fa-clock me-1"></i>Pending
                    </span>
                </div>
                <hr>
                <div class="text-center">
                    <div class="realtime-clock mb-2"></div>
                    <small class="text-muted last-updated-time">Last Updated: {{ now()->format('M d, Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row g-3 g-lg-4 mb-4">
    <!-- Recent Teachers -->
    <div class="col-12 col-lg-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 py-3" style="background-color: #1a202c;">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Recent Teachers
                    </h6>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-external-link-alt me-1"></i>
                        <span class="d-none d-sm-inline">View All</span>
                        <span class="d-sm-none">All</span>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($recentTeachers->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentTeachers as $teacher)
                            @if($teacher && $teacher->created_at)
                            <div class="list-group-item border-0 p-3 hover-item">
                                <div class="d-flex justify-content-between align-items-start align-items-sm-center">
                                    <div class="d-flex align-items-center flex-grow-1 me-3">
                                        <div class="me-3 position-relative flex-shrink-0">
                                            @if($teacher->profile_picture)
                                                <img src="{{ Storage::url($teacher->profile_picture) }}" 
                                                     class="rounded-circle shadow-sm" width="45" height="45" alt="Profile">
                                            @else
                                                <div class="bg-gradient-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                                     style="width: 45px; height: 45px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                            <span class="position-absolute bottom-0 end-0 bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }} border border-white rounded-circle" 
                                                  style="width: 12px; height: 12px;"></span>
                                        </div>
                                        <div class="min-w-0 flex-grow-1">
                                            <h6 class="mb-1 fw-bold text-truncate">{{ $teacher->name }}</h6>
                                            <small class="text-muted d-block text-truncate">{{ $teacher->department }}</small>
                                            <div class="mt-1 d-none d-md-block">
                                                <small class="text-primary">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Joined {{ $teacher->created_at ? $teacher->created_at->diffForHumans() : 'Recently' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end flex-shrink-0">
                                        <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }} mb-1">
                                            {{ ucfirst($teacher->status) }}
                                        </span>
                                        <div class="d-none d-sm-block">
                                            <small class="text-muted">ID: #{{ $teacher->id }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-user-plus fa-3x text-muted"></i>
                        </div>
                        <h6 class="text-muted mb-2">No Teachers Registered</h6>
                        <p class="text-muted small mb-3 px-3">Start building your educational team by adding teachers.</p>
                        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add First Teacher
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 py-3" style="background-color: #1a202c;">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-book me-2"></i>
                        Recent Courses
                    </h6>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-external-link-alt me-1"></i>
                        <span class="d-none d-sm-inline">View All</span>
                        <span class="d-sm-none">All</span>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($recentCourses->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentCourses as $course)
                            @if($course && $course->created_at)
                            <div class="list-group-item border-0 p-3 hover-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center flex-grow-1 me-3">
                                        <div class="me-3 flex-shrink-0">
                                            @if($course->thumbnail)
                                                <img src="{{ $course->thumbnail }}" 
                                                     class="rounded shadow-sm" width="55" height="45" alt="Course thumbnail"
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-gradient-primary rounded d-flex align-items-center justify-content-center shadow-sm" 
                                                     style="width: 55px; height: 45px;">
                                                    <i class="fas fa-book text-white"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-grow-1">
                                            <h6 class="mb-1 fw-bold text-truncate">{{ Str::limit($course->title, 30) }}</h6>
                                            <small class="text-muted d-block text-truncate">
                                                <i class="fas fa-user me-1"></i>
                                                by {{ $course->instructor->name ?? 'Unknown' }}
                                            </small>
                                            <div class="mt-1 d-none d-md-block">
                                                <small class="text-info">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $course->created_at ? $course->created_at->diffForHumans() : 'Recently' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end flex-shrink-0">
                                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : ($course->status === 'draft' ? 'warning' : 'secondary') }} mb-1">
                                            {{ ucfirst($course->status) }}
                                        </span>
                                        <div class="d-none d-sm-block">
                                            @if($course->is_free)
                                                <small class="text-success fw-bold">FREE</small>
                                            @else
                                                <small class="text-primary fw-bold">${{ number_format($course->price, 0) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-book fa-3x text-muted"></i>
                        </div>
                        <h6 class="text-muted mb-2">No Courses Available</h6>
                        <p class="text-muted small mb-3 px-3">Courses will appear here once teachers start creating content.</p>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-warning">
                            <i class="fas fa-eye me-1"></i>View Course Management
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Activity Feed -->
<div class="row g-3 g-lg-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 py-3" style="background-color: #1a202c;">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-stream me-2"></i>
                        Recent Activity
                    </h6>
                    <button class="btn btn-sm btn-outline-light">
                        <i class="fas fa-sync-alt me-1"></i>
                        <span class="d-none d-sm-inline">Refresh</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline-responsive">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start">
                                <div class="flex-grow-1 me-3">
                                    <h6 class="mb-1">New Teacher Registration</h6>
                                    <p class="text-muted mb-1 small">{{ $recentTeachers->first()?->name ?? 'John Doe' }} joined the platform</p>
                                </div>
                                <small class="text-muted flex-shrink-0">{{ $recentTeachers->first()?->created_at?->diffForHumans() ?? '2 hours ago' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start">
                                <div class="flex-grow-1 me-3">
                                    <h6 class="mb-1">Course Published</h6>
                                    <p class="text-muted mb-1 small">{{ $recentCourses->first()?->title ?? 'Introduction to Programming' }} was published</p>
                                </div>
                                <small class="text-muted flex-shrink-0">{{ $recentCourses->first()?->created_at?->diffForHumans() ?? '5 hours ago' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start">
                                <div class="flex-grow-1 me-3">
                                    <h6 class="mb-1">System Update</h6>
                                    <p class="text-muted mb-1 small">Platform updated to version 2.1.0</p>
                                </div>
                                <small class="text-muted flex-shrink-0">1 day ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Performance -->
<div class="row g-3 g-lg-4">
    <!-- Quick Actions -->
    <div class="col-12 col-xl-8 mb-3 mb-xl-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 py-3" style="background-color: #1a202c;">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center py-3 py-lg-4 border-2 hover-lift text-decoration-none">
                            <i class="fas fa-chalkboard-teacher fa-2x mb-2 mb-lg-3 text-primary"></i>
                            <span class="fw-bold small">Manage Teachers</span>
                            <small class="text-muted mt-1 d-none d-lg-block">View & Edit</small>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center py-3 py-lg-4 border-2 hover-lift text-decoration-none">
                            <i class="fas fa-book fa-2x mb-2 mb-lg-3 text-warning"></i>
                            <span class="fw-bold small">Manage Courses</span>
                            <small class="text-muted mt-1 d-none d-lg-block">Content Control</small>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center py-3 py-lg-4 border-2 hover-lift text-decoration-none">
                            <i class="fas fa-users fa-2x mb-2 mb-lg-3 text-info"></i>
                            <span class="fw-bold small">View Students</span>
                            <small class="text-muted mt-1 d-none d-lg-block">Monitor & Edit</small>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a href="#" class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center py-3 py-lg-4 border-2 hover-lift text-decoration-none">
                            <i class="fas fa-chart-bar fa-2x mb-2 mb-lg-3 text-success"></i>
                            <span class="fw-bold small">View Reports</span>
                            <small class="text-muted mt-1 d-none d-lg-block">Analytics</small>
                        </a>
                    </div>
                </div>
                
                <!-- Additional Actions Row -->
                <div class="row g-3">
                    <div class="col-6 col-lg-3">
                        <a href="#" class="btn btn-outline-dark w-100 h-100 d-flex flex-column justify-content-center py-3 py-lg-4 border-2 hover-lift text-decoration-none">
                            <i class="fas fa-cog fa-2x mb-2 mb-lg-3 text-dark"></i>
                            <span class="fw-bold small">System Settings</span>
                            <small class="text-muted mt-1 d-none d-lg-block">Configuration</small>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a href="#" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column justify-content-center py-3 py-lg-4 border-2 hover-lift text-decoration-none">
                            <i class="fas fa-database fa-2x mb-2 mb-lg-3 text-secondary"></i>
                            <span class="fw-bold small">Backup Data</span>
                            <small class="text-muted mt-1 d-none d-lg-block">Data Safety</small>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a href="#" class="btn btn-outline-danger w-100 h-100 d-flex flex-column justify-content-center py-3 py-lg-4 border-2 hover-lift text-decoration-none">
                            <i class="fas fa-shield-alt fa-2x mb-2 mb-lg-3 text-danger"></i>
                            <span class="fw-bold small">Security Logs</span>
                            <small class="text-muted mt-1 d-none d-lg-block">Monitor Access</small>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a href="#" class="btn btn-outline-purple w-100 h-100 d-flex flex-column justify-content-center py-3 py-lg-4 border-2 hover-lift text-decoration-none">
                            <i class="fas fa-bell fa-2x mb-2 mb-lg-3" style="color: #6f42c1;"></i>
                            <span class="fw-bold small">Notifications</span>
                            <small class="text-muted mt-1 d-none d-lg-block">Alerts & Messages</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 py-3" style="background-color: #1a202c;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Performance Metrics
                    </h6>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success ms-2 performance-live-indicator">
                            <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i>
                            Live
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="performance-metrics-container">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Teacher Engagement</span>
                            <span class="fw-bold text-success" id="teacher-engagement-percent">{{ $performanceMetrics['teacher_engagement'] }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" id="teacher-engagement-bar" data-metric="teacher_engagement" style="width: {{ $performanceMetrics['teacher_engagement'] }}%"></div>
                        </div>
                        <small class="text-muted" id="teacher-engagement-desc">{{ $stats['active_teachers'] }} of {{ $stats['total_teachers'] }} teachers active</small>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Course Publication Rate</span>
                            <span class="fw-bold text-primary" id="course-publication-percent">{{ $performanceMetrics['course_publication_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" id="course-publication-bar" data-metric="course_publication_rate" style="width: {{ $performanceMetrics['course_publication_rate'] }}%"></div>
                        </div>
                        <small class="text-muted" id="course-publication-desc">{{ $stats['published_courses'] }} of {{ $stats['total_courses'] }} courses published</small>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Enrollment Rate</span>
                            <span class="fw-bold text-warning" id="enrollment-rate-percent">{{ $performanceMetrics['student_course_ratio'] }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" id="enrollment-rate-bar" data-metric="student_course_ratio" style="width: {{ $performanceMetrics['student_course_ratio'] }}%"></div>
                        </div>
                        <small class="text-muted" id="enrollment-rate-desc">{{ $stats['total_enrollments'] }} total enrollments</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Platform Growth (30 days)</span>
                            <span class="fw-bold text-{{ $performanceMetrics['platform_growth'] >= 0 ? 'success' : 'danger' }}" id="platform-growth-percent">
                                {{ $performanceMetrics['platform_growth'] >= 0 ? '+' : '' }}{{ $performanceMetrics['platform_growth'] }}%
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $performanceMetrics['platform_growth'] >= 0 ? 'info' : 'danger' }}" 
                                 id="platform-growth-bar" data-metric="platform_growth" style="width: {{ abs($performanceMetrics['platform_growth']) }}%"></div>
                        </div>
                        <small class="text-muted" id="platform-growth-desc">Compared to previous month</small>
                    </div>
                    
                    <div class="text-center mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Last Updated:</small>
                            <small class="text-primary fw-bold" id="performance-last-updated">Just now</small>
                        </div>
                        <button class="btn btn-sm btn-outline-light w-100" onclick="refreshPerformanceMetrics()">
                            <i class="fas fa-sync-alt me-1"></i>
                            Refresh Metrics
                        </button>
                    </div>
                </div>
                
                <!-- Loading State -->
                <div id="performance-loading" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Updating metrics...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Enhanced Responsive Styles */
    .hover-card {
        transition: all 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .hover-item {
        transition: background-color 0.3s ease;
    }
    .hover-item:hover {
        background-color: #f8f9fa;
    }
    
    .hover-lift {
        transition: all 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
    }
    
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    /* Responsive icon sizing */
    @media (max-width: 768px) {
        .icon-shape {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        .fs-5 {
            font-size: 0.9rem !important;
        }
    }
    
    /* Enhanced gradient backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #56CCF2 0%, #2F80ED 100%);
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #FFD89B 0%, #19547B 100%);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    /* Responsive timeline */
    .timeline-responsive {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline-responsive::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .timeline-marker {
        position: absolute;
        left: -1.75rem;
        top: 0.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    
    .timeline-content {
        background: #fff;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-left: 3px solid #e9ecef;
    }
    
    /* Mobile responsive timeline */
    @media (max-width: 576px) {
        .timeline-responsive {
            padding-left: 1.5rem;
        }
        
        .timeline-responsive::before {
            left: 0.75rem;
        }
        
        .timeline-marker {
            left: -1.25rem;
            width: 10px;
            height: 10px;
        }
        
        .timeline-content {
            padding: 0.75rem;
        }
    }
    
    /* Enhanced responsive borders */
    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #dee2e6 !important;
        }
    }
    
    /* Responsive text sizing */
    @media (min-width: 992px) {
        .h3-lg {
            font-size: calc(1.3rem + 0.6vw) !important;
        }
        .fs-4-lg {
            font-size: 1.5rem !important;
        }
    }
    
    /* Enhanced card spacing */
    .card-body {
        padding: 1.25rem;
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 1rem;
        }
    }
    
    /* Flexible content layout */
    .min-w-0 {
        min-width: 0;
    }
    
    .flex-shrink-0 {
        flex-shrink: 0;
    }
    
    /* Enhanced button styles */
    .btn-outline-primary:hover,
    .btn-outline-warning:hover,
    .btn-outline-info:hover,
    .btn-outline-success:hover {
        transform: translateY(-1px);
    }
    
    /* Dropdown active state */
    .dropdown-item.active {
        background-color: #667eea;
        color: white;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    .dropdown-item.active:hover {
        background-color: #5a67d8;
        color: white;
    }
    
    /* Custom gap utilities for better spacing */
    .g-3 {
        --bs-gutter-x: 1rem;
        --bs-gutter-y: 1rem;
    }
    
    .g-lg-4 {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 1.5rem;
    }
    
    @media (min-width: 992px) {
        .g-lg-4 {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 1.5rem;
        }
    }
    
    /* Enhanced progress bars */
    .progress {
        background-color: #f8f9fa;
        border-radius: 0.375rem;
    }
    
    .progress-bar {
        border-radius: 0.375rem;
    }
    
    /* Better mobile card stacking */
    @media (max-width: 991.98px) {
        .card {
            margin-bottom: 1rem;
        }
    }
    
    /* Enhanced dropdown positioning */
    .dropdown-menu-end {
        --bs-position: end;
    }
    
    @media (max-width: 576px) {
        .dropdown-menu {
            font-size: 0.875rem;
        }
    }
    
    /* Purple button styling */
    .btn-outline-purple {
        border-color: #6f42c1;
        color: #6f42c1;
    }
    
    .btn-outline-purple:hover {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
        transform: translateY(-1px);
    }
    
    /* Additional chart styling */
    .chart-container {
        position: relative;
        width: 100%;
        height: auto;
    }
    
    /* Enhanced mobile responsiveness for new sections */
    @media (max-width: 576px) {
        .card-header h6 {
            font-size: 0.9rem;
        }
        
        .progress {
            height: 6px !important;
        }
        
        .badge {
            font-size: 0.75rem;
        }
    }
    
    /* Animation for new charts */
    @keyframes chartFadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .chart-container canvas {
        animation: chartFadeIn 1s ease-out;
    }
    
    /* Real-time animations */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    @keyframes glow {
        0% { box-shadow: 0 0 5px rgba(102, 126, 234, 0.5); }
        50% { box-shadow: 0 0 15px rgba(102, 126, 234, 0.8); }
        100% { box-shadow: 0 0 5px rgba(102, 126, 234, 0.5); }
    }
    
    .realtime-indicator {
        animation: glow 2s ease-in-out infinite;
    }
    
    .realtime-clock {
        font-family: 'Courier New', monospace;
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        padding: 0.5rem;
        border-radius: 0.375rem;
        font-weight: bold;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }
    
    /* Enhanced hover effects for new sections */
    .hover-item {
        transition: all 0.3s ease;
    }
    
    .hover-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Platform Overview Chart with Real Data
    const ctx = document.getElementById('platformChart').getContext('2d');
    
    // Real data from backend
    let chartData = @json($chartData);
    let platformChart;

    function initChart() {
        platformChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'New Enrollments',
                        data: chartData.enrollments,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'New Courses',
                        data: chartData.courses,
                        borderColor: '#48bb78',
                        backgroundColor: 'rgba(72, 187, 120, 0.1)',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#48bb78',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'New Teachers',
                        data: chartData.teachers,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            title: function(context) {
                                return 'Period: ' + context[0].label;
                            },
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            },
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    },
                    line: {
                        borderWidth: 3
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    function updateChart(newData) {
        platformChart.data.labels = newData.labels;
        platformChart.data.datasets[0].data = newData.enrollments;
        platformChart.data.datasets[1].data = newData.courses;
        platformChart.data.datasets[2].data = newData.teachers;
        platformChart.update('active');
    }

    function loadChartData(period) {
        // Show loading state
        const dropdownButton = document.getElementById('periodDropdown');
        const originalHTML = dropdownButton.innerHTML;
        dropdownButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Loading...';
        dropdownButton.disabled = true;

        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = token ? token.getAttribute('content') : '';

        fetch(`{{ route('admin.dashboard.chart-data') }}?period=${period}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                updateChart(data);
                // Update period text safely
                const periodTexts = {
                    'week': ['This Week', 'Week'],
                    'month': ['This Month', 'Month'],
                    'year': ['This Year', 'Year']
                };
                const viewTexts = {
                    'week': '7 Days View',
                    'month': '6 Months View',
                    'year': '12 Months View'
                };
                
                // Update period text safely with a small delay to ensure DOM is ready
                setTimeout(() => {
                    const periodTextElement = document.getElementById('periodText');
                    const periodTextShortElement = document.getElementById('periodTextShort');
                    const chartViewTextElement = document.getElementById('chartViewText');
                    
                    try {
                        if (periodTextElement) {
                            periodTextElement.textContent = periodTexts[period][0];
                        }
                        if (periodTextShortElement) {
                            periodTextShortElement.textContent = periodTexts[period][1];
                        }
                        if (chartViewTextElement) {
                            chartViewTextElement.textContent = viewTexts[period];
                        }
                    } catch (error) {
                        console.error('Error updating period text:', error);
                    }
                }, 10);
                
                // Update active state
                document.querySelectorAll('.period-option').forEach(option => {
                    option.classList.remove('active');
                });
                document.querySelector(`[data-period="${period}"]`).classList.add('active');
                
                // Restore button
                dropdownButton.innerHTML = originalHTML;
                dropdownButton.disabled = false;
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
                // Restore button on error
                dropdownButton.innerHTML = originalHTML;
                dropdownButton.disabled = false;
                
                // Show detailed error message
                console.error('Full error details:', {
                    message: error.message,
                    stack: error.stack,
                    period: period,
                    url: `{{ route('admin.dashboard.chart-data') }}?period=${period}`
                });
                
                alert(`Failed to load chart data for period: ${period}. Check console for details.`);
            });
    }

    // Real-time dashboard data refresh
    function refreshDashboardData() {
        // Show subtle loading indicator for statistics
        const statsCards = document.querySelectorAll('.hover-card');
        statsCards.forEach(card => {
            card.style.opacity = '0.7';
            card.style.transition = 'opacity 0.3s ease';
        });

        const token = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = token ? token.getAttribute('content') : '';

        fetch(`{{ route('admin.dashboard.realtime-stats') }}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatisticsCards(data.stats);
                updatePerformanceMetrics(data.performanceMetrics); // Add back performance metrics update
                updateLastUpdatedTime();
                showRealTimeIndicator();
                
                // Show success indicator briefly
                showSuccessNotification('Dashboard data updated', 2000);
            }
        })
        .catch(error => {
            console.error('Error refreshing dashboard data:', error);
            showErrorNotification('Failed to refresh dashboard data');
        })
        .finally(() => {
            // Restore card opacity
            statsCards.forEach(card => {
                card.style.opacity = '1';
            });
        });
    }

    function updateStatisticsCards(stats) {
        // Update statistics with smooth animation
        updateCounterWithAnimation('.total-teachers', stats.total_teachers);
        updateCounterWithAnimation('.active-teachers', stats.active_teachers);
        updateCounterWithAnimation('.total-courses', stats.total_courses);
        updateCounterWithAnimation('.total-students', stats.total_students);
    }

    function updateCounterWithAnimation(selector, newValue) {
        const element = document.querySelector(selector);
        if (!element) return;

        const currentValue = parseInt(element.textContent) || 0;
        const increment = newValue > currentValue ? 1 : -1;
        const steps = Math.abs(newValue - currentValue);
        let current = currentValue;
        let step = 0;

        const timer = setInterval(() => {
            current += increment;
            element.textContent = current;
            step++;
            
            if (step >= steps) {
                clearInterval(timer);
                element.textContent = newValue;
                
                // Add flash effect for changes
                if (newValue !== currentValue) {
                    element.style.background = '#e3f2fd';
                    setTimeout(() => {
                        element.style.background = 'transparent';
                    }, 1000);
                }
            }
        }, 50);
    }

    function updateLastUpdatedTime() {
        const timeElements = document.querySelectorAll('.last-updated-time');
        const now = new Date().toLocaleString();
        timeElements.forEach(element => {
            element.textContent = `Last updated: ${now}`;
        });
    }

    function showRealTimeIndicator() {
        const indicators = document.querySelectorAll('.realtime-indicator');
        indicators.forEach(indicator => {
            indicator.style.animation = 'pulse 0.5s ease-in-out';
            setTimeout(() => {
                indicator.style.animation = '';
            }, 500);
        });
    }

    function updateRealTimeClock() {
        const clockElements = document.querySelectorAll('.realtime-clock');
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        const dateString = now.toLocaleDateString();
        
        clockElements.forEach(element => {
            element.innerHTML = `
                <div class="text-center">
                    <div class="fw-bold">${timeString}</div>
                    <small class="text-muted">${dateString}</small>
                </div>
            `;
        });
    }

    // Initialize real-time updates on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Setup CSRF token for AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios = window.axios || {};
            window.axios.defaults = window.axios.defaults || {};
            window.axios.defaults.headers = window.axios.defaults.headers || {};
            window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        }

        // Setup fetch default headers for CSRF
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            if (args[1]) {
                args[1].headers = args[1].headers || {};
                args[1].headers['X-CSRF-TOKEN'] = token ? token.getAttribute('content') : '';
            } else {
                args[1] = {
                    headers: {
                        'X-CSRF-TOKEN': token ? token.getAttribute('content') : ''
                    }
                };
            }
            return originalFetch.apply(this, args);
        };

        initChart();

        // Add event listeners to dropdown options
        document.querySelectorAll('.period-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const period = this.dataset.period;
                loadChartData(period);
            });
        });
        
        // Check if all dashboard components are loaded
        const checkDashboardLoaded = () => {
            const chartCanvas = document.getElementById('platformChart');
            const performanceMetrics = document.getElementById('performance-metrics-container');
            const statsCards = document.querySelectorAll('.hover-card').length;
            
            if (chartCanvas && performanceMetrics && statsCards >= 4) {
                // All main components are present, mark page as loaded
                setTimeout(() => {
                    pageLoadComplete();
                }, 500); // Small delay to ensure rendering is complete
                return true;
            }
            return false;
        };
        
        // Check every 100ms until loaded
        const loadCheckInterval = setInterval(() => {
            if (checkDashboardLoaded()) {
                clearInterval(loadCheckInterval);
            }
        }, 100);
        
        // Fallback timeout
        setTimeout(() => {
            clearInterval(loadCheckInterval);
            if (document.getElementById('globalLoadingScreen').style.display !== 'none') {
                pageLoadComplete();
            }
        }, 5000); // 5 second fallback
    });

    // Real-time updates - refresh dashboard data every 30 seconds
    setInterval(function() {
        refreshDashboardData();
        autoRefreshPerformanceMetrics(); // Also refresh performance metrics
    }, 30000); // 30 seconds

    // Real-time performance metrics update every 15 seconds
    setInterval(function() {
        autoRefreshPerformanceMetrics();
    }, 15000); // 15 seconds

    // Auto-refresh chart data every 2 minutes for real-time feeling
    setInterval(function() {
        const activePeriod = document.querySelector('.period-option.active').dataset.period;
        loadChartData(activePeriod);
    }, 120000); // 2 minutes

    // Real-time clock update
    setInterval(function() {
        updateRealTimeClock();
    }, 1000); // 1 second

    // Performance Metrics Functions
    function autoRefreshPerformanceMetrics() {
        fetch('{{ route("admin.dashboard.performance-metrics") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updatePerformanceMetrics(data.performanceMetrics, data.metricsDetails);
                updatePerformanceTimestamp(data.lastUpdated);
                
                // Flash the live indicator
                const liveIndicator = document.querySelector('.performance-live-indicator');
                if (liveIndicator) {
                    liveIndicator.style.backgroundColor = '#28a745';
                    setTimeout(() => {
                        liveIndicator.style.backgroundColor = '';
                    }, 1000);
                }
            } else {
                console.error('Failed to fetch performance metrics:', data.error);
            }
        })
        .catch(error => {
            console.error('Performance metrics fetch error:', error);
        });
    }

    function refreshPerformanceMetrics() {
        fetch('{{ route("admin.dashboard.performance-metrics") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updatePerformanceMetrics(data.performanceMetrics, data.metricsDetails);
                updatePerformanceTimestamp(data.lastUpdated);
                
                // Flash the live indicator
                const liveIndicator = document.querySelector('.performance-live-indicator');
                if (liveIndicator) {
                    liveIndicator.style.backgroundColor = '#28a745';
                    setTimeout(() => {
                        liveIndicator.style.backgroundColor = '';
                    }, 1000);
                }
            } else {
                console.error('Failed to fetch performance metrics:', data.error);
            }
        })
        .catch(error => {
            console.error('Performance metrics fetch error:', error);
        });
    }

    function updatePerformanceMetrics(metrics, details = null) {
        // If details are provided, use them. Otherwise, construct basic descriptions
        const defaultDetails = {
            teacher_engagement: { description: `Teacher engagement: ${metrics.teacher_engagement}%` },
            course_publication_rate: { description: `Course publication: ${metrics.course_publication_rate}%` },
            enrollment_rate: { description: `Enrollment rate: ${metrics.student_course_ratio}%` },
            platform_growth: { description: `Platform growth: ${metrics.platform_growth}%` }
        };
        
        const metricsDetails = details || defaultDetails;
        
        // Update Teacher Engagement
        updateMetric('teacher-engagement', metrics.teacher_engagement, metricsDetails.teacher_engagement.description);
        
        // Update Course Publication Rate
        updateMetric('course-publication', metrics.course_publication_rate, metricsDetails.course_publication_rate.description);
        
        // Update Enrollment Rate
        updateMetric('enrollment-rate', metrics.student_course_ratio, metricsDetails.enrollment_rate.description);
        
        // Update Platform Growth
        updatePlatformGrowth(metrics.platform_growth, metricsDetails.platform_growth.description);
    }

    function updateMetric(metricName, percentage, description) {
        // Update percentage text
        const percentElement = document.getElementById(metricName + '-percent');
        if (percentElement) {
            animateNumber(percentElement, parseInt(percentElement.textContent), percentage, '%');
        }
        
        // Update progress bar with animation
        const barElement = document.getElementById(metricName + '-bar');
        if (barElement) {
            animateProgressBar(barElement, percentage);
        }
        
        // Update description
        const descElement = document.getElementById(metricName + '-desc');
        if (descElement) {
            descElement.textContent = description;
        }
    }

    function updatePlatformGrowth(percentage, description) {
        const percentElement = document.getElementById('platform-growth-percent');
        const barElement = document.getElementById('platform-growth-bar');
        const descElement = document.getElementById('platform-growth-desc');
        
        if (percentElement) {
            // Update color based on positive/negative growth
            const isPositive = percentage >= 0;
            percentElement.className = 'fw-bold ' + (isPositive ? 'text-success' : 'text-danger');
            percentElement.textContent = (isPositive ? '+' : '') + percentage + '%';
        }
        
        if (barElement) {
            // Update progress bar color and width
            const isPositive = percentage >= 0;
            barElement.className = 'progress-bar ' + (isPositive ? 'bg-info' : 'bg-danger');
            animateProgressBar(barElement, Math.abs(percentage));
        }
        
        if (descElement) {
            descElement.textContent = description;
        }
    }

    function animateNumber(element, startValue, endValue, suffix = '') {
        const duration = 1000; // 1 second
        const startTime = performance.now();
        
        function updateNumber(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const currentValue = Math.round(startValue + (endValue - startValue) * easeOutQuart);
            
            element.textContent = currentValue + suffix;
            
            if (progress < 1) {
                requestAnimationFrame(updateNumber);
            }
        }
        
        requestAnimationFrame(updateNumber);
    }

    function animateProgressBar(element, targetWidth) {
        const currentWidth = parseFloat(element.style.width) || 0;
        const duration = 1000; // 1 second
        const startTime = performance.now();
        
        function updateProgress(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const width = currentWidth + (targetWidth - currentWidth) * easeOutQuart;
            
            element.style.width = width + '%';
            
            if (progress < 1) {
                requestAnimationFrame(updateProgress);
            }
        }
        
        requestAnimationFrame(updateProgress);
    }

    function updatePerformanceTimestamp(timestamp) {
        const timestampElement = document.getElementById('performance-last-updated');
        if (timestampElement) {
            timestampElement.textContent = 'Just now';
            
            // Show full timestamp after a delay
            setTimeout(() => {
                const date = new Date();
                timestampElement.textContent = date.toLocaleTimeString();
            }, 2000);
        }
    }

    // Manual refresh function for button click
    function refreshPerformanceMetrics() {
        const container = document.getElementById('performance-metrics-container');
        const loading = document.getElementById('performance-loading');
        
        // Show loading state
        if (container && loading) {
            container.style.display = 'none';
            loading.style.display = 'block';
        }
        
        fetch('{{ route("admin.dashboard.performance-metrics") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updatePerformanceMetrics(data.performanceMetrics, data.metricsDetails);
                updatePerformanceTimestamp(data.lastUpdated);
            } else {
                console.error('Failed to fetch performance metrics:', data.error);
            }
        })
        .catch(error => {
            console.error('Performance metrics fetch error:', error);
        })
        .finally(() => {
            // Hide loading state
            if (container && loading) {
                loading.style.display = 'none';
                container.style.display = 'block';
            }
        });
    }
    
    // Demo function to test loading screen (remove in production)
    function testLoadingScreen() {
        showNavigationLoading();
        
        // Test the actual loading route
        performAjaxRequest('{{ route("admin.dashboard.test-loading") }}', {
            timeout: 15000 // 15 seconds timeout for testing
        })
        .then(data => {
            showSuccessNotification(data.message || 'Loading test completed!');
            console.log('Test loading data:', data);
        })
        .catch(error => {
            console.error('Loading test failed:', error);
            showErrorNotification('Loading test failed: ' + error.message);
        })
        .finally(() => {
            hideLoadingScreen();
        });
    }
    
    // Add test button for loading screen (remove in production)
    document.addEventListener('DOMContentLoaded', function() {
        // Add a test button to the page header
        setTimeout(() => {
            const header = document.querySelector('.d-flex .btn-group');
            if (header) {
                const testBtn = document.createElement('button');
                testBtn.className = 'btn btn-outline-info btn-sm ms-2';
                testBtn.innerHTML = '<i class="fas fa-vial me-1"></i>Test Loading';
                testBtn.onclick = testLoadingScreen;
                header.parentNode.appendChild(testBtn);
            }
        }, 1000);
    });
</script>
@endpush
</div>
@endsection
