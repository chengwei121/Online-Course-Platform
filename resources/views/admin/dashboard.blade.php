@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header')
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-3"></i>
        Admin Dashboard
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshDashboard()" id="refreshBtn">
                <i class="fas fa-sync-alt me-1"></i>
                Refresh
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>PDF Report</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Excel Export</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-chart-line me-2"></i>Analytics Report</a></li>
            </ul>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#quickActionsModal">
            <i class="fas fa-plus me-1"></i>
            Quick Actions
        </button>
    </div>
@endsection

@section('content')
<div data-page-loaded="true">
    <!-- Dashboard Stats Row -->
    <div class="row mb-4" id="statsContainer">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="enhanced-stat-card students-card">
                <div class="stat-card-overlay"></div>
                <div class="stat-card-content">
                    <div class="stat-header">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-gradient-primary">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="stat-icon-bg">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-body">
                        <div class="stat-title">Total Students</div>
                        <div class="stat-number" id="totalStudents">
                            {{ $stats['total_students'] ?? 0 }}
                        </div>
                        <div class="stat-progress">
                            <div class="progress-bar" style="width: 75%"></div>
                        </div>
                        <div class="stat-info">
                            <div class="stat-trend positive">
                                <i class="fas fa-arrow-up"></i>
                                <span id="studentGrowth">{{ $growthTrends['students'] ?? 0 }}%</span>
                            </div>
                            <div class="stat-meta">vs last month</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="enhanced-stat-card teachers-card">
                <div class="stat-card-overlay"></div>
                <div class="stat-card-content">
                    <div class="stat-header">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-gradient-success">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="stat-icon-bg">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-body">
                        <div class="stat-title">Total Teachers</div>
                        <div class="stat-number" id="totalTeachers">
                            {{ $stats['total_teachers'] ?? 0 }}
                        </div>
                        <div class="stat-progress">
                            <div class="progress-bar" style="width: 60%"></div>
                        </div>
                        <div class="stat-info">
                            <div class="stat-badge active">
                                <span id="activeTeachers">{{ $stats['active_teachers'] ?? 0 }}</span> Active
                            </div>
                            <div class="stat-meta">currently teaching</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="enhanced-stat-card courses-card">
                <div class="stat-card-overlay"></div>
                <div class="stat-card-content">
                    <div class="stat-header">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-gradient-info">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stat-icon-bg">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-body">
                        <div class="stat-title">Total Courses</div>
                        <div class="stat-number" id="totalCourses">
                            {{ $stats['total_courses'] ?? 0 }}
                        </div>
                        <div class="stat-progress">
                            <div class="progress-bar" style="width: 85%"></div>
                        </div>
                        <div class="stat-info">
                            <div class="stat-badge published">
                                <span id="publishedCourses">{{ $stats['published_courses'] ?? 0 }}</span> Published
                            </div>
                            <div class="stat-meta">available for students</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="enhanced-stat-card enrollments-card">
                <div class="stat-card-overlay"></div>
                <div class="stat-card-content">
                    <div class="stat-header">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-gradient-warning">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-icon-bg">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-body">
                        <div class="stat-title">Total Enrollments</div>
                        <div class="stat-number" id="totalEnrollments">
                            {{ $stats['total_enrollments'] ?? 0 }}
                        </div>
                        <div class="stat-progress">
                            <div class="progress-bar" style="width: 92%"></div>
                        </div>
                        <div class="stat-info">
                            <div class="stat-trend positive">
                                <i class="fas fa-arrow-up"></i>
                                <span id="enrollmentGrowth">{{ $growthTrends['enrollments'] ?? 0 }}%</span>
                            </div>
                            <div class="stat-meta">vs last month</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity Row -->
    <div class="row mb-4">
        <!-- Analytics Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-area me-2"></i>
                        Platform Analytics
                    </h6>
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="chartPeriod" id="weekBtn" value="week">
                        <label class="btn btn-outline-light btn-sm" for="weekBtn">Week</label>
                        
                        <input type="radio" class="btn-check" name="chartPeriod" id="monthBtn" value="month" checked>
                        <label class="btn btn-outline-light btn-sm" for="monthBtn">Month</label>
                        
                        <input type="radio" class="btn-check" name="chartPeriod" id="yearBtn" value="year">
                        <label class="btn btn-outline-light btn-sm" for="yearBtn">Year</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="analyticsChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col">
                                <span class="badge bg-primary me-1">●</span>
                                <small class="text-muted">Students</small>
                            </div>
                            <div class="col">
                                <span class="badge bg-success me-1">●</span>
                                <small class="text-muted">Teachers</small>
                            </div>
                            <div class="col">
                                <span class="badge bg-info me-1">●</span>
                                <small class="text-muted">Courses</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100" style="overflow: visible !important;">
                <div class="card-header modern-grey-header text-white d-flex justify-content-between align-items-center" style="overflow: visible !important; position: relative; z-index: 1060;">
                    <div>
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-pulse me-2"></i>
                            Recent Activity
                        </h6>
                        <small class="opacity-75">Live platform updates</small>
                    </div>
                    <div class="dropdown" style="position: relative; z-index: 1060;">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="activityFilterBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="position: absolute !important; z-index: 1060 !important; top: 100% !important; right: 0 !important; left: auto !important;">
                            <li><a class="dropdown-item" href="#" onclick="filterActivity('all')">
                                <i class="fas fa-list me-2"></i>All Activity
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterActivity('teachers')">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Teachers Only
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterActivity('courses')">
                                <i class="fas fa-book me-2"></i>Courses Only
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterActivity('enrollments')">
                                <i class="fas fa-user-graduate me-2"></i>Enrollments Only
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterActivity('reviews')">
                                <i class="fas fa-star me-2"></i>Reviews Only
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="activity-timeline" style="max-height: 420px; overflow-y: auto;">
                        
                        <!-- Recent Enrollments Timeline - Only show if there are enrollments -->
                        @if(!empty($recentEnrollments) && count($recentEnrollments) > 0)
                        <div class="timeline-section" data-category="enrollments">
                            @foreach($recentEnrollments as $index => $enrollment)
                            <div class="timeline-item {{ $index === 0 ? 'latest' : '' }}">
                                <div class="timeline-marker">
                                    <div class="timeline-icon bg-primary">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    @if(!$loop->last || !empty($recentReviews) || !empty($recentTeachers) || !empty($recentCourses))
                                    <div class="timeline-line"></div>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="timeline-title mb-1">New Enrollment</h6>
                                            <span class="timeline-badge badge bg-primary">
                                                <i class="fas fa-graduation-cap me-1"></i>Student
                                            </span>
                                        </div>
                                        <p class="timeline-description">
                                            <strong>{{ $enrollment->user->name ?? 'Student' }}</strong> enrolled in 
                                            <em>{{ Str::limit($enrollment->course->title ?? 'Course', 25) }}</em>
                                        </p>
                                        <div class="timeline-meta">
                                            <span class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $enrollment->created_at ? $enrollment->created_at->diffForHumans() : 'Recently' }}
                                            </span>
                                            <span class="text-muted ms-3">
                                                <i class="fas fa-coins me-1"></i>
                                                RM{{ number_format($enrollment->course->price ?? 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Recent Reviews Timeline - Only show if there are reviews -->
                        @if(!empty($recentReviews) && count($recentReviews) > 0)
                        <div class="timeline-section" data-category="reviews">
                            @foreach($recentReviews as $index => $review)
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <div class="timeline-icon bg-warning">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    @if(!$loop->last || !empty($recentTeachers) || !empty($recentCourses))
                                    <div class="timeline-line"></div>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="timeline-title mb-1">New Review</h6>
                                            <span class="timeline-badge badge bg-warning">
                                                <i class="fas fa-star me-1"></i>
                                                {{ $review->rating }}/5 Stars
                                            </span>
                                        </div>
                                        <p class="timeline-description">
                                            <strong>{{ $review->user->name ?? 'Student' }}</strong> reviewed 
                                            <em>{{ Str::limit($review->course->title ?? 'Course', 25) }}</em>
                                            @if($review->comment)
                                            <br><small class="text-muted">"{{ Str::limit($review->comment, 50) }}"</small>
                                            @endif
                                        </p>
                                        <div class="timeline-meta">
                                            <span class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $review->created_at ? $review->created_at->diffForHumans() : 'Recently' }}
                                            </span>
                                            <span class="text-muted ms-3">
                                                <i class="fas fa-chalkboard-teacher me-1"></i>
                                                {{ $review->course->instructor->name ?? 'Unknown Instructor' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Recent Teachers Timeline - Only show if there are teachers -->
                        @if(!empty($recentTeachers) && count($recentTeachers) > 0)
                        <div class="timeline-section" data-category="teachers">
                            @foreach($recentTeachers as $index => $teacher)
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <div class="timeline-icon bg-success">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    @if(!$loop->last || !empty($recentCourses))
                                    <div class="timeline-line"></div>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="timeline-title mb-1">New Teacher</h6>
                                            <span class="timeline-badge badge bg-success">
                                                <i class="fas fa-user-check me-1"></i>
                                                {{ ucfirst($teacher->status ?? 'active') }}
                                            </span>
                                        </div>
                                        <p class="timeline-description">
                                            <strong>{{ $teacher->name }}</strong> joined as instructor
                                            @if($teacher->department)
                                            in <em>{{ $teacher->department }}</em>
                                            @endif
                                        </p>
                                        <div class="timeline-meta">
                                            <span class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $teacher->created_at ? $teacher->created_at->diffForHumans() : 'Recently' }}
                                            </span>
                                            @if($teacher->courses_count ?? 0 > 0)
                                            <span class="text-muted ms-3">
                                                <i class="fas fa-book me-1"></i>
                                                {{ $teacher->courses_count }} courses
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Recent Courses Timeline - Only show if there are courses -->
                        @if(!empty($recentCourses) && count($recentCourses) > 0)
                        <div class="timeline-section" data-category="courses">
                            @foreach($recentCourses as $index => $course)
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <div class="timeline-icon bg-info">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    @if(!$loop->last)
                                    <div class="timeline-line"></div>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="timeline-title mb-1">New Course</h6>
                                            <span class="timeline-badge badge bg-{{ $course->status === 'published' ? 'success' : 'warning' }}">
                                                <i class="fas fa-{{ $course->status === 'published' ? 'check-circle' : 'clock' }} me-1"></i>
                                                {{ ucfirst($course->status ?? 'draft') }}
                                            </span>
                                        </div>
                                        <p class="timeline-description">
                                            <strong>{{ Str::limit($course->title, 35) }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                by {{ $course->instructor->name ?? 'Unknown Instructor' }}
                                            </small>
                                        </p>
                                        <div class="timeline-meta">
                                            <span class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $course->created_at ? $course->created_at->diffForHumans() : 'Recently' }}
                                            </span>
                                            @if($course->enrollments_count ?? 0 > 0)
                                            <span class="text-muted ms-3">
                                                <i class="fas fa-users me-1"></i>
                                                {{ $course->enrollments_count }} enrolled
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- No Enrollments Message - Show when enrollments filter is selected but no enrollments exist -->
                        <div class="text-center py-5 no-enrollments-message" style="display: none;">
                            <div class="text-muted">
                                <i class="fas fa-user-graduate fa-3x mb-3 opacity-50"></i>
                                <h5 class="text-muted mb-2">No Recent Enrollments</h5>
                                <p class="mb-0">When students enroll in courses, their activity will appear here.</p>
                            </div>
                        </div>

                        <!-- No Recent Activity Message - Show when no sections have data -->
                        @if((empty($recentEnrollments) || count($recentEnrollments) == 0) && 
                            (empty($recentReviews) || count($recentReviews) == 0) && 
                            (empty($recentTeachers) || count($recentTeachers) == 0) && 
                            (empty($recentCourses) || count($recentCourses) == 0))
                        <div class="text-center py-5 no-activity-message">
                            <div class="text-muted">
                                <i class="fas fa-history fa-3x mb-3 opacity-50"></i>
                                <h5 class="text-muted mb-2">No Recent Activity</h5>
                                <p class="mb-0">New enrollments, reviews, teachers, and courses will appear here when available.</p>
                            </div>
                        </div>
                        @endif

                        <!-- Load More Button - Only show if there might be more data -->
                        @php
                            $hasMoreEnrollments = !empty($recentEnrollments) && count($recentEnrollments) >= 5;
                            $hasMoreReviews = !empty($recentReviews) && count($recentReviews) >= 5;
                            $hasMoreTeachers = !empty($recentTeachers) && count($recentTeachers) >= 5;
                            $hasMoreCourses = !empty($recentCourses) && count($recentCourses) >= 5;
                            $showLoadMore = $hasMoreEnrollments || $hasMoreReviews || $hasMoreTeachers || $hasMoreCourses;
                        @endphp
                        
                        @if($showLoadMore)
                        <div class="timeline-item text-center">
                            <button class="btn btn-outline-primary btn-sm" onclick="loadMoreActivity()">
                                <i class="fas fa-plus me-1"></i>
                                Load More Activity
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <small class="text-muted">
                        <i class="fas fa-sync-alt me-1"></i>
                        Auto-refreshes every 30 seconds
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Modal -->
<div class="modal fade" id="quickActionsModal" tabindex="-1" aria-labelledby="quickActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="quickActionsModalLabel">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions Panel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="font-weight-bold mb-3">User Management</h6>
                        <div class="list-group">
                            <a href="{{ route('admin.teachers.create') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-user-plus me-2"></i>Add New Teacher
                            </a>
                            <a href="{{ route('admin.clients.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-users me-2"></i>Manage Students
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-user-shield me-2"></i>Admin Permissions
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="font-weight-bold mb-3">Content Management</h6>
                        <div class="list-group">
                            <a href="{{ route('admin.courses.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-book me-2"></i>Manage Courses
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-folder me-2"></i>Categories
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-file-alt me-2"></i>Lessons
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="font-weight-bold mb-3">Reports & Analytics</h6>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action" onclick="generateReport()">
                                <i class="fas fa-chart-bar me-2"></i>Performance Report
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-chart-pie me-2"></i>User Analytics
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-download me-2"></i>Export Data
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="font-weight-bold mb-3">System Tools</h6>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action" onclick="clearCache()">
                                <i class="fas fa-trash me-2"></i>Clear Cache
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-database me-2"></i>Backup Data
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" onclick="systemSettings()">
                                <i class="fas fa-cogs me-2"></i>System Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Modern Grey Card Header */
.modern-grey-header {
    background: linear-gradient(135deg, #64748b 0%, #475569 50%, #334155 100%);
    border-bottom: 3px solid #1e293b;
    position: relative;
    overflow: hidden;
}

.modern-grey-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.1) 50%, rgba(255, 255, 255, 0.1) 75%, transparent 75%);
    background-size: 20px 20px;
    opacity: 0.3;
    pointer-events: none;
}

.modern-grey-header h6 {
    position: relative;
    z-index: 2;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    letter-spacing: 0.5px;
}

.modern-grey-header i {
    position: relative;
    z-index: 2;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
}

.modern-grey-header small {
    position: relative;
    z-index: 2;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

/* Button styling on grey header */
.modern-grey-header .btn-outline-light {
    position: relative;
    z-index: 2;
    border-color: rgba(255, 255, 255, 0.5);
    color: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
}

.modern-grey-header .btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.8);
    color: white;
}

.modern-grey-header .btn-check:checked + .btn-outline-light {
    background-color: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.8);
    color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Activity Filter Dropdown Fixes */
.modern-grey-header .dropdown {
    position: relative;
    z-index: 1060;
}

.modern-grey-header .dropdown-menu {
    border: 1px solid rgba(0, 0, 0, 0.15);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    z-index: 1060 !important;
    min-width: 200px;
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    transform: none !important;
    margin-top: 0.125rem;
}

.modern-grey-header .dropdown-item {
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.modern-grey-header .dropdown-item:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.modern-grey-header .dropdown-item:active {
    background-color: #e9ecef;
    color: #495057;
}

.modern-grey-header .dropdown-item i {
    width: 20px;
    text-align: center;
    filter: none;
    text-shadow: none;
}

/* Ensure dropdown doesn't get cut off */
.card-header .dropdown {
    position: relative;
    z-index: 1060;
}

.card-header .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    transform: none !important;
    margin-top: 0.125rem;
    z-index: 1060 !important;
}

/* Fix card overflow issues */
.card {
    overflow: visible !important;
}

.card-header {
    overflow: visible !important;
    position: relative;
    z-index: 10;
}

.card-body {
    position: relative;
    z-index: 1;
}

/* Recent Activity specific fixes */
.col-lg-4 .card {
    overflow: visible !important;
}

.col-lg-4 .card-header {
    overflow: visible !important;
}

.activity-timeline {
    position: relative;
    z-index: 1;
}

/* Bootstrap 5 dropdown override for activity filter */
#activityFilterBtn + .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    z-index: 1060 !important;
    transform: none !important;
    margin-top: 0.125rem !important;
    inset: auto 0px auto auto !important;
}

/* Force dropdown to stay visible */
.dropdown-menu.show {
    display: block !important;
    position: absolute !important;
    z-index: 1060 !important;
}

/* Row and column overflow fixes */
.row {
    overflow: visible !important;
}

.col-lg-4, .col-lg-8 {
    overflow: visible !important;
}

/* Additional dropdown positioning fixes */
@media (min-width: 992px) {
    .col-lg-4 .dropdown-menu {
        right: 0 !important;
        left: auto !important;
    }
}

/* Ensure parent containers don't clip dropdown */
.container-fluid {
    overflow: visible !important;
}

.main-content {
    overflow: visible !important;
}

/* Enhanced Dashboard Stats Styles */
.enhanced-stat-card {
    position: relative;
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    height: 180px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.enhanced-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    pointer-events: none;
    z-index: 1;
}

.stat-card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    z-index: 2;
}

.students-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.teachers-card {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

.courses-card {
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    color: white;
}

.enrollments-card {
    background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
    color: white;
}

.enhanced-stat-card:hover .stat-card-overlay {
    background: rgba(255, 255, 255, 0.05);
}

.stat-card-content {
    position: relative;
    z-index: 3;
    height: 100%;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
}

.stat-header {
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.stat-icon-wrapper {
    position: relative;
    width: 60px;
    height: 60px;
}

.stat-icon {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    z-index: 2;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.stat-icon-bg {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: rgba(255, 255, 255, 0.1);
    z-index: 1;
}

.enhanced-stat-card:hover .stat-icon {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}


.stat-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.stat-title {
    font-size: 0.9rem;
    font-weight: 500;
    opacity: 0.9;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stat-progress {
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.stat-progress .progress-bar {
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 2px;
    position: relative;
}

.stat-progress .progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
}

.stat-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
}

.stat-trend.positive {
    background: rgba(72, 187, 120, 0.3);
}

.stat-trend.negative {
    background: rgba(245, 101, 101, 0.3);
}

.stat-badge {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
}

.stat-badge.active {
    background: rgba(72, 187, 120, 0.3);
}

.stat-badge.published {
    background: rgba(66, 153, 225, 0.3);
}

.stat-meta {
    font-size: 0.75rem;
    opacity: 0.8;
    font-weight: 400;
}

/* Enhanced responsive design */
@media (max-width: 1200px) {
    .enhanced-stat-card {
        height: 160px;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .stat-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
}

@media (max-width: 768px) {
    .enhanced-stat-card {
        height: 140px;
        margin-bottom: 1rem;
    }
    
    .stat-card-content {
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 1.8rem;
    }
    
    .stat-icon-wrapper {
        width: 40px;
        height: 40px;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .stat-icon-bg {
        width: 60px;
        height: 60px;
        font-size: 30px;
    }
    
    .stat-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}

/* Loading state for stats */
.enhanced-stat-card.loading .stat-number {
    background: #f8f9fa;
    border-radius: 4px;
}

/* Timeline activity styles without animations */

/* Border left styles for compatibility */
.border-left-primary {
    border-left: 0.25rem solid var(--primary-color) !important;
}
.border-left-success {
    border-left: 0.25rem solid var(--success-color) !important;
}
.border-left-info {
    border-left: 0.25rem solid var(--info-color) !important;
}
.border-left-warning {
    border-left: 0.25rem solid var(--warning-color) !important;
}

/* Enhanced Performance Metrics Styles */
.metric-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    height: 100%;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    border-color: #cbd5e0;
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--info-color));
}

.metric-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    color: white;
    font-size: 20px;
}

.metric-info {
    flex: 1;
}

.metric-title {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 4px 0;
    color: #2d3748;
}

.metric-subtitle {
    font-size: 13px;
    color: #718096;
    margin: 0;
}

.metric-body {
    text-align: center;
}

.metric-chart {
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
}

/* Enhanced Progress Ring Styles */
.progress-ring {
    position: relative;
    display: inline-block;
}

.progress-ring-svg {
    transform: rotate(-90deg);
}

.progress-ring-circle-bg {
    fill: transparent;
    stroke: #e2e8f0;
    stroke-width: 8;
}

.progress-ring-circle {
    fill: transparent;
    stroke: var(--primary-color);
    stroke-width: 8;
    stroke-linecap: round;
    stroke-dasharray: 314;
    stroke-dashoffset: 314;
    transition: stroke-dashoffset 1.5s ease, stroke 0.3s ease;
}

.progress-ring-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.progress-ring-text .percentage {
    display: block;
    font-size: 20px;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.progress-ring-text .label {
    display: block;
    font-size: 12px;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 4px;
}

/* Metric Details */
.metric-details {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    gap: 12px;
}

.detail-item {
    text-align: center;
}

.detail-value {
    display: block;
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
    line-height: 1;
}

.detail-label {
    display: block;
    font-size: 11px;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
}

.detail-divider {
    color: #cbd5e0;
    font-weight: 300;
    font-size: 16px;
}

/* Metric Trend */
.metric-trend {
    padding: 8px 12px;
    background: #f7fafc;
    border-radius: 8px;
    font-size: 12px;
    color: #718096;
    display: inline-block;
}

/* Performance Summary */
.performance-summary {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e2e8f0;
}

.summary-header h6 {
    color: #2d3748;
}

/* Insight Cards */
.insight-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    margin-bottom: 16px;
    transition: all 0.3s ease;
}

.insight-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.insight-card.excellent {
    border-left: 4px solid #48bb78;
    background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
}

.insight-card.good {
    border-left: 4px solid #4299e1;
    background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
}

.insight-card.attention {
    border-left: 4px solid #f59e0b;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
}

.insight-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    font-size: 18px;
}

.insight-card.excellent .insight-icon {
    background: #48bb78;
    color: white;
}

.insight-card.good .insight-icon {
    background: #4299e1;
    color: white;
}

.insight-card.attention .insight-icon {
    background: #f59e0b;
    color: white;
}

.insight-content h6 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 4px 0;
    color: #2d3748;
}

.insight-content p {
    font-size: 13px;
    color: #718096;
    margin: 0;
}

/* Specific metric card colors */
.teacher-engagement .progress-ring-circle {
    stroke: #48bb78;
}

.course-publication .progress-ring-circle {
    stroke: #4299e1;
}

.student-enrollment .progress-ring-circle {
    stroke: #4a5568;
}

.platform-growth .progress-ring-circle {
    stroke: #f59e0b;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .metric-card {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .metric-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .metric-title {
        font-size: 14px;
    }
    
    .metric-subtitle {
        font-size: 12px;
    }
    
    .progress-ring-svg {
        width: 100px;
        height: 100px;
    }
    
    .progress-ring-text .percentage {
        font-size: 16px;
    }
    
    .detail-value {
        font-size: 16px;
    }
    
    .performance-summary {
        padding: 20px;
    }
    
    .insight-card {
        padding: 16px;
        flex-direction: column;
        text-align: center;
    }
    
    .insight-icon {
        margin-right: 0;
        margin-bottom: 12px;
    }
}

/* Animation for progress rings */
@keyframes progressRingAnimation {
    from {
        stroke-dashoffset: 314;
    }
    to {
        stroke-dashoffset: var(--target-offset);
    }
}

/* Loading animation for metrics */
.metric-card.loading .progress-ring-circle {
    stroke-dasharray: 314;
    stroke-dashoffset: 314;
    animation: rotate 2s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Hover effects for progress rings */
.metric-card:hover .progress-ring-circle {
    stroke-width: 10;
    filter: drop-shadow(0 0 8px rgba(74, 85, 104, 0.3));
}

/* Progress Circles */
.progress-circle {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto;
}

.progress-circle-inner {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: conic-gradient(var(--primary-color) 0deg, #e9ecef 0deg);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.progress-circle-inner::before {
    content: '';
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: white;
    position: absolute;
}

.progress-circle-text {
    position: relative;
    z-index: 1;
    font-weight: bold;
    font-size: 14px;
    color: var(--primary-color);
}

/* Activity Feed */
.activity-feed {
    border-radius: 0.375rem;
}

.activity-item {
    transition: background-color 0.2s ease;
}

.activity-item:hover {
    background-color: #f8f9fa;
}

.activity-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

/* Status Indicators */
.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

.notification-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}

/* Chart Container */
.chart-container {
    position: relative;
    height: 400px;
    width: 100%;
}

/* Quick Action Buttons */
.btn-outline-success:hover,
.btn-outline-info:hover,
.btn-outline-primary:hover,
.btn-outline-warning:hover,
.btn-outline-danger:hover,
.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .progress-circle {
        width: 60px;
        height: 60px;
    }
    
    .progress-circle-inner::before {
        width: 45px;
        height: 45px;
    }
    
    .progress-circle-text {
        font-size: 12px;
    }
    
    .activity-icon,
    .notification-icon {
        width: 28px;
        height: 28px;
        font-size: 10px;
    }
}

/* Loading states */
.card.loading {
    position: relative;
    pointer-events: none;
}

.card.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
    border-radius: inherit;
}

.card.loading::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 30px;
    height: 30px;
    margin: -15px 0 0 -15px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    z-index: 11;
}

/* Gradient backgrounds */
.bg-gradient-primary {
    background: linear-gradient(45deg, var(--primary-color), #667eea);
}

.bg-gradient-success {
    background: linear-gradient(45deg, var(--success-color), #68d391);
}

.bg-gradient-info {
    background: linear-gradient(45deg, var(--info-color), #63b3ed);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, var(--warning-color), #fbbf24);
}

.bg-gradient-dark {
    background: linear-gradient(45deg, #2d3748, #4a5568);
}

/* Enhanced Timeline Activity Styles */
.activity-timeline {
    position: relative;
    padding: 1rem 0;
}

.timeline-section {
    position: relative;
}

.timeline-item {
    position: relative;
    display: flex;
    margin-bottom: 1.5rem;
    padding-left: 1rem;
    padding-right: 1rem;
}

.timeline-item .timeline-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    position: relative;
}

.timeline-item .timeline-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    border-color: #cbd5e0;
}

.timeline-item .timeline-card::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 12px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 8px 8px 8px 0;
    border-color: transparent #ffffff transparent transparent;
    z-index: 2;
}

.timeline-item .timeline-card::after {
    content: '';
    position: absolute;
    left: -9px;
    top: 12px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 8px 8px 8px 0;
    border-color: transparent #e2e8f0 transparent transparent;
    z-index: 1;
}

.timeline-marker {
    position: relative;
    flex-shrink: 0;
    width: 40px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 8px;
}

.timeline-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    position: relative;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    border: 3px solid white;
}

.timeline-line {
    position: absolute;
    left: 50%;
    top: 40px;
    width: 2px;
    height: calc(100% + 24px);
    background: linear-gradient(to bottom, #e2e8f0, #cbd5e0);
    transform: translateX(-50%);
    z-index: 1;
}

.timeline-content {
    flex: 1;
    margin-left: 1rem;
    position: relative;
}

.timeline-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    position: relative;
}

.timeline-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    border-color: #cbd5e0;
}

.timeline-card::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 12px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 8px 8px 8px 0;
    border-color: transparent #ffffff transparent transparent;
    z-index: 2;
}

.timeline-card::after {
    content: '';
    position: absolute;
    left: -9px;
    top: 12px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 8px 8px 8px 0;
    border-color: transparent #e2e8f0 transparent transparent;
    z-index: 1;
}

.timeline-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.timeline-description {
    font-size: 0.85rem;
    color: #4a5568;
    line-height: 1.4;
    margin-bottom: 0.75rem;
}

.timeline-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: #718096;
}

.timeline-badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-weight: 500;
    white-space: nowrap;
}

/* Timeline Animation Keyframes - Removed */

.timeline-section.filtered-out {
    display: none;
}

/* Scrollbar styling for timeline */
.activity-timeline::-webkit-scrollbar {
    width: 6px;
}

.activity-timeline::-webkit-scrollbar-track {
    background: #f1f3f4;
    border-radius: 3px;
}

.activity-timeline::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.activity-timeline::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive timeline adjustments */
@media (max-width: 768px) {
    .timeline-marker {
        width: 30px;
    }
    
    .timeline-icon {
        width: 24px;
        height: 24px;
        font-size: 10px;
        border-width: 2px;
    }
    
    .timeline-content {
        margin-left: 0.5rem;
    }
    
    .timeline-card {
        padding: 0.75rem;
    }
    
    .timeline-title {
        font-size: 0.85rem;
    }
    
    .timeline-description {
        font-size: 0.8rem;
    }
}
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Global variables
let analyticsChart = null;
let chartData = @json($chartData ?? []);
let currentPeriod = 'month';

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for Bootstrap to fully load
    setTimeout(() => {
        initializeDashboard();
        
        // Restore state after load more
        restoreStateAfterReload();
    }, 100);
    
    // Additional fallback for dropdown initialization
    setTimeout(() => {
        ensureDropdownsWork();
    }, 500);
});

// Ensure dropdowns are working with fallback
function ensureDropdownsWork() {
    const activityFilterBtn = document.getElementById('activityFilterBtn');
    if (activityFilterBtn) {
        // Check if Bootstrap dropdown is working
        try {
            let dropdown = bootstrap.Dropdown.getInstance(activityFilterBtn);
            if (!dropdown) {
                dropdown = new bootstrap.Dropdown(activityFilterBtn);
                console.log('Activity filter dropdown initialized with fallback');
            }
            
            // Add click event listener as backup
            const dropdownItems = activityFilterBtn.parentElement.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Extract filter category from onclick attribute
                    const onclick = this.getAttribute('onclick');
                    if (onclick) {
                        const match = onclick.match(/filterActivity\('([^']+)'\)/);
                        if (match) {
                            filterActivity(match[1]);
                        }
                    }
                });
            });
            
        } catch (error) {
            console.error('Error initializing dropdown:', error);
            
            // Fallback: manual toggle
            activityFilterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const menu = this.nextElementSibling;
                if (menu && menu.classList.contains('dropdown-menu')) {
                    menu.classList.toggle('show');
                }
            });
        }
    }
}

function initializeDashboard() {
    // Initialize chart
    initializeChart();
    
    // Initialize chart period toggles
    initializeChartToggles();
    
    // Initialize dropdown functionality
    initializeDropdowns();
    
    // Start real-time updates
    startRealtimeUpdates();
}

// Initialize all Bootstrap dropdowns
function initializeDropdowns() {
    // Initialize all dropdowns on the page
    const dropdownElements = document.querySelectorAll('[data-bs-toggle="dropdown"]');
    dropdownElements.forEach(element => {
        if (!bootstrap.Dropdown.getInstance(element)) {
            new bootstrap.Dropdown(element);
        }
    });
    
    // Specific initialization for activity filter dropdown
    const activityFilterBtn = document.getElementById('activityFilterBtn');
    if (activityFilterBtn && !bootstrap.Dropdown.getInstance(activityFilterBtn)) {
        const dropdown = new bootstrap.Dropdown(activityFilterBtn, {
            boundary: 'viewport',
            display: 'dynamic'
        });
        
        // Add event listeners
        activityFilterBtn.addEventListener('shown.bs.dropdown', function () {
            console.log('Activity filter dropdown opened');
            
            // Ensure dropdown is positioned correctly
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu) {
                dropdownMenu.style.position = 'absolute';
                dropdownMenu.style.zIndex = '1060';
                dropdownMenu.style.top = '100%';
                dropdownMenu.style.right = '0';
                dropdownMenu.style.left = 'auto';
                dropdownMenu.style.transform = 'none';
            }
        });
        
        activityFilterBtn.addEventListener('hidden.bs.dropdown', function () {
            console.log('Activity filter dropdown closed');
        });
    }
}

// Restore filter state and scroll position after reload
function restoreStateAfterReload() {
    const loadMoreClicked = sessionStorage.getItem('loadMoreClicked');
    
    if (loadMoreClicked) {
        // Restore filter
        const savedFilter = sessionStorage.getItem('dashboardFilter');
        if (savedFilter && savedFilter !== 'all') {
            setTimeout(() => {
                filterActivity(savedFilter);
            }, 100);
        }
        
        // Restore scroll position
        const scrollPosition = sessionStorage.getItem('scrollPosition');
        if (scrollPosition) {
            setTimeout(() => {
                window.scrollTo(0, parseInt(scrollPosition));
            }, 200);
        }
        
        // Check if load more button should be hidden
        setTimeout(() => {
            const loadMoreBtn = document.querySelector('.timeline-item.text-center button');
            if (loadMoreBtn && !hasMoreData()) {
                loadMoreBtn.closest('.timeline-item').style.display = 'none';
                showSuccessNotification('All available activity loaded');
            } else if (loadMoreBtn) {
                showSuccessNotification('Dashboard refreshed');
            }
        }, 300);
        
        // Clear session storage
        sessionStorage.removeItem('loadMoreClicked');
        sessionStorage.removeItem('dashboardFilter');
        sessionStorage.removeItem('scrollPosition');
    }
}

// Check if there's more data to load
function hasMoreData() {
    const enrollmentItems = document.querySelectorAll('.timeline-section[data-category="enrollments"] .timeline-item').length;
    const teacherItems = document.querySelectorAll('.timeline-section[data-category="teachers"] .timeline-item').length;
    const courseItems = document.querySelectorAll('.timeline-section[data-category="courses"] .timeline-item').length;
    
    // If any category has exactly 5 items, there might be more
    return enrollmentItems >= 5 || teacherItems >= 5 || courseItems >= 5;
}



function initializeChart() {
    const ctx = document.getElementById('analyticsChart');
    if (!ctx) return;
    
    console.log('Initializing chart with data:', chartData);
    
    analyticsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels || [],
            datasets: [
                {
                    label: 'Enrollments',
                    data: chartData.enrollments || [],
                    borderColor: '#4a5568',
                    backgroundColor: 'rgba(74, 85, 104, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Courses',
                    data: chartData.courses || [],
                    borderColor: '#4299e1',
                    backgroundColor: 'rgba(66, 153, 225, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Teachers',
                    data: chartData.teachers || [],
                    borderColor: '#48bb78',
                    backgroundColor: 'rgba(72, 187, 120, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e2e8f0'
                    }
                },
                x: {
                    grid: {
                        color: '#e2e8f0'
                    }
                }
            },
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 6
                }
            }
        }
    });
}

function initializeProgressCircles() {
    // Initialize enhanced progress rings
    document.querySelectorAll('.progress-ring').forEach(ring => {
        const percent = ring.dataset.percent || 0;
        const color = ring.dataset.color || '#4a5568';
        const circle = ring.querySelector('.progress-ring-circle');
        
        if (circle) {
            // Set the stroke color
            circle.style.stroke = color;
            
            // Calculate the circumference (2 * π * r, where r = 50)
            const circumference = 2 * Math.PI * 50;
            
            // Calculate the offset based on percentage
            const offset = circumference - (percent / 100) * circumference;
            
            // Set initial state
            circle.style.strokeDasharray = circumference;
            circle.style.strokeDashoffset = circumference;
            
            // Animate to the target
            setTimeout(() => {
                circle.style.strokeDashoffset = offset;
            }, 500);
        }
    });

    // Initialize legacy progress circles for compatibility
    document.querySelectorAll('.progress-circle').forEach(circle => {
        const percent = circle.dataset.percent || 0;
        const inner = circle.querySelector('.progress-circle-inner');
        
        if (inner) {
            const deg = (percent / 100) * 360;
            inner.style.background = `conic-gradient(var(--primary-color) ${deg}deg, #e9ecef ${deg}deg)`;
        }
    });
}

function refreshMetrics() {
    // Add loading state to all metric cards
    document.querySelectorAll('.metric-card').forEach(card => {
        card.classList.add('loading');
    });
    
    // Fetch new performance metrics
    fetch('{{ route('admin.dashboard.performance-metrics') }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.performanceMetrics) {
            updatePerformanceMetrics(data.performanceMetrics);
            updateInsightCards(data.performanceMetrics);
            
            // Remove loading state
            document.querySelectorAll('.metric-card').forEach(card => {
                card.classList.remove('loading');
            });
            
            showSuccessNotification('Performance metrics refreshed successfully');
        }
    })
    .catch(error => {
        console.error('Error refreshing metrics:', error);
        
        // Remove loading state
        document.querySelectorAll('.metric-card').forEach(card => {
            card.classList.remove('loading');
        });
        
        showErrorNotification('Failed to refresh performance metrics');
    });
}

function exportMetrics() {
    // Show loading notification
    showSuccessNotification('Preparing performance metrics report for download...');
    
    // Simulate export process
    setTimeout(() => {
        // Create a simple CSV export
        const metricsData = [
            ['Metric', 'Value', 'Status'],
            ['Teacher Engagement', document.querySelector('.teacher-engagement .percentage')?.textContent || '0%', 'Active'],
            ['Course Publication', document.querySelector('.course-publication .percentage')?.textContent || '0%', 'Published'],
            ['Student Enrollment', document.querySelector('.student-enrollment .percentage')?.textContent || '0%', 'Enrolled'],
            ['Platform Growth', document.querySelector('.platform-growth .percentage')?.textContent || '0%', 'Growing']
        ];
        
        const csvContent = metricsData.map(row => row.join(',')).join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `performance_metrics_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        showSuccessNotification('Performance metrics report downloaded successfully');
    }, 1500);
}

function viewDetailedAnalytics() {
    // Show modal with detailed analytics
    const modalHtml = `
        <div class="modal fade" id="detailedAnalyticsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-chart-line me-2"></i>
                            Detailed Performance Analytics
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Historical Trends</h6>
                                <canvas id="detailedChart" height="200"></canvas>
                            </div>
                            <div class="col-md-6">
                                <h6>Performance Breakdown</h6>
                                <div class="detailed-metrics">
                                    <div class="metric-detail-item">
                                        <span class="metric-name">Teacher Engagement Rate</span>
                                        <div class="metric-bar">
                                            <div class="metric-fill" style="width: ${document.querySelector('.teacher-engagement .percentage')?.textContent || '0%'}"></div>
                                        </div>
                                    </div>
                                    <div class="metric-detail-item">
                                        <span class="metric-name">Course Publication Rate</span>
                                        <div class="metric-bar">
                                            <div class="metric-fill" style="width: ${document.querySelector('.course-publication .percentage')?.textContent || '0%'}"></div>
                                        </div>
                                    </div>
                                    <div class="metric-detail-item">
                                        <span class="metric-name">Student Enrollment Rate</span>
                                        <div class="metric-bar">
                                            <div class="metric-fill" style="width: ${document.querySelector('.student-enrollment .percentage')?.textContent || '0%'}"></div>
                                        </div>
                                    </div>
                                    <div class="metric-detail-item">
                                        <span class="metric-name">Platform Growth Rate</span>
                                        <div class="metric-bar">
                                            <div class="metric-fill" style="width: ${Math.abs(parseInt(document.querySelector('.platform-growth .percentage')?.textContent || '0'))}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="exportMetrics()">
                            <i class="fas fa-download me-1"></i>Export Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('detailedAnalyticsModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('detailedAnalyticsModal'));
    modal.show();
    
    // Add styles for the detailed metrics
    const style = document.createElement('style');
    style.textContent = `
        .detailed-metrics {
            padding: 20px 0;
        }
        
        .metric-detail-item {
            margin-bottom: 20px;
        }
        
        .metric-name {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2d3748;
        }
        
        .metric-bar {
            height: 12px;
            background: #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .metric-fill {
            height: 100%;
            background: linear-gradient(90deg, #4a5568, #667eea);
            border-radius: 6px;
            transition: width 1s ease;
        }
    `;
    document.head.appendChild(style);
}

function updateInsightCards(metrics) {
    // Update insight cards based on performance metrics
    const insightCards = document.querySelectorAll('.insight-card');
    
    // Teacher engagement insight
    if (metrics.teacher_engagement >= 80) {
        updateInsightCard(0, 'excellent', 'Excellent Performance', 'Teacher engagement is above 80%', 'fas fa-trophy');
    } else if (metrics.teacher_engagement >= 60) {
        updateInsightCard(0, 'good', 'Good Performance', 'Teacher engagement is healthy', 'fas fa-thumbs-up');
    } else {
        updateInsightCard(0, 'attention', 'Needs Attention', 'Teacher engagement needs improvement', 'fas fa-exclamation-triangle');
    }
    
    // Course publication insight
    if (metrics.course_publication_rate >= 70) {
        updateInsightCard(1, 'excellent', 'Strong Publishing', 'Course publication rate is excellent', 'fas fa-trophy');
    } else if (metrics.course_publication_rate >= 50) {
        updateInsightCard(1, 'good', 'Steady Progress', 'Course publication is on track', 'fas fa-thumbs-up');
    } else {
        updateInsightCard(1, 'attention', 'Publishing Gap', 'More courses need to be published', 'fas fa-chart-line');
    }
    
    // Growth insight
    if (metrics.platform_growth > 0) {
        updateInsightCard(2, 'excellent', 'Positive Growth', `Platform growing by ${metrics.platform_growth}%`, 'fas fa-rocket');
    } else if (metrics.platform_growth === 0) {
        updateInsightCard(2, 'good', 'Stable Platform', 'Platform maintaining stability', 'fas fa-equals');
    } else {
        updateInsightCard(2, 'attention', 'Growth Challenge', 'Platform needs growth focus', 'fas fa-chart-line');
    }
}

function updateInsightCard(index, type, title, description, icon) {
    const cards = document.querySelectorAll('.insight-card');
    if (cards[index]) {
        const card = cards[index];
        
        // Remove existing type classes
        card.classList.remove('excellent', 'good', 'attention');
        card.classList.add(type);
        
        // Update icon
        const iconElement = card.querySelector('.insight-icon i');
        if (iconElement) {
            iconElement.className = icon;
        }
        
        // Update content
        const titleElement = card.querySelector('.insight-content h6');
        const descElement = card.querySelector('.insight-content p');
        
        if (titleElement) titleElement.textContent = title;
        if (descElement) descElement.textContent = description;
    }
}

function initializeChartToggles() {
    document.querySelectorAll('input[name="chartPeriod"]').forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                updateChart(this.value);
            }
        });
    });
}

function updateChart(period) {
    currentPeriod = period;
    
    // Show loading state
    const chartContainer = document.querySelector('.chart-container');
    chartContainer.classList.add('loading');
    
    // Fetch new chart data
    fetch(`{{ route('admin.dashboard.chart-data') }}?period=${period}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Chart data received:', data);
        console.log('Period:', period);
        
        if (analyticsChart) {
            analyticsChart.data.labels = data.labels || [];
            analyticsChart.data.datasets[0].data = data.enrollments || [];
            analyticsChart.data.datasets[1].data = data.courses || [];
            analyticsChart.data.datasets[2].data = data.teachers || [];
            analyticsChart.update();
        }
        
        chartContainer.classList.remove('loading');
        showSuccessNotification('Chart updated successfully');
    })
    .catch(error => {
        console.error('Error updating chart:', error);
        chartContainer.classList.remove('loading');
        showErrorNotification('Failed to update chart data');
    });
}

function startRealtimeUpdates() {
    // Update stats every 30 seconds
    setInterval(updateRealtimeStats, 30000);
}

function updateRealtimeStats() {
    fetch('{{ route('admin.dashboard.realtime-stats') }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.stats) {
            updateStatsDisplay(data.stats);
            updatePerformanceMetrics(data.performanceMetrics);
            
            // Update recent activity with real-time data
            if (data.recentActivity) {
                updateRecentActivity(data.recentActivity);
            }
        }
    })
    .catch(error => {
        console.log('Realtime update failed:', error);
    });
}

function updateStatsDisplay(stats) {
    // Update stat numbers directly without animation
    if (document.getElementById('totalStudents')) {
        document.getElementById('totalStudents').textContent = stats.total_students.toLocaleString();
    }
    if (document.getElementById('totalTeachers')) {
        document.getElementById('totalTeachers').textContent = stats.total_teachers.toLocaleString();
    }
    if (document.getElementById('totalCourses')) {
        document.getElementById('totalCourses').textContent = stats.total_courses.toLocaleString();
    }
    if (document.getElementById('totalEnrollments')) {
        document.getElementById('totalEnrollments').textContent = stats.total_enrollments.toLocaleString();
    }
    
    // Update additional info
    if (document.getElementById('activeTeachers')) {
        document.getElementById('activeTeachers').textContent = stats.active_teachers;
    }
    if (document.getElementById('publishedCourses')) {
        document.getElementById('publishedCourses').textContent = stats.published_courses;
    }
}

function updatePerformanceMetrics(metrics) {
    if (!metrics) return;
    
    // Update enhanced progress rings
    const progressRings = document.querySelectorAll('.progress-ring');
    const values = [
        metrics.teacher_engagement,
        metrics.course_publication_rate,
        metrics.student_course_ratio,
        Math.abs(metrics.platform_growth)
    ];
    
    progressRings.forEach((ring, index) => {
        if (values[index] !== undefined) {
            const percent = values[index];
            const circle = ring.querySelector('.progress-ring-circle');
            const text = ring.querySelector('.progress-ring-text .percentage');
            
            if (circle && text) {
                // Calculate the circumference (2 * π * r, where r = 50)
                const circumference = 2 * Math.PI * 50;
                
                // Calculate the offset based on percentage
                const offset = circumference - (percent / 100) * circumference;
                
                // Update the progress ring
                circle.style.strokeDashoffset = offset;
                
                // Update the text with animation
                animateValue(text, parseInt(text.textContent), percent, 1000, '%');
            }
        }
    });

    // Update legacy progress circles for compatibility
    document.querySelectorAll('.progress-circle').forEach((circle, index) => {
        const values = [
            metrics.teacher_engagement,
            metrics.course_publication_rate,
            metrics.student_course_ratio,
            Math.abs(metrics.platform_growth)
        ];
        
        if (values[index] !== undefined) {
            const percent = values[index];
            const inner = circle.querySelector('.progress-circle-inner');
            const text = circle.querySelector('.progress-circle-text');
            
            if (inner && text) {
                const deg = (percent / 100) * 360;
                inner.style.background = `conic-gradient(var(--primary-color) ${deg}deg, #e9ecef ${deg}deg)`;
                text.textContent = percent + '%';
            }
        }
    });

    // Update insight cards
    updateInsightCards(metrics);
}

function updateRecentActivity(recentActivity) {
    if (!recentActivity) return;
    
    const timeline = document.querySelector('.activity-timeline');
    if (!timeline) return;
    
    // Clear existing activity
    const existingSections = timeline.querySelectorAll('.timeline-section');
    existingSections.forEach(section => section.remove());
    
    // Clear no activity messages
    const noActivityMessages = timeline.querySelectorAll('.no-activity-message, .no-enrollments-message');
    noActivityMessages.forEach(msg => msg.remove());
    
    let hasAnyActivity = false;
    
    // Add enrollments if any
    if (recentActivity.enrollments && recentActivity.enrollments.length > 0) {
        hasAnyActivity = true;
        const enrollmentSection = createEnrollmentSection(recentActivity.enrollments);
        timeline.insertBefore(enrollmentSection, timeline.querySelector('.timeline-item.text-center') || null);
    }
    
    // Add reviews if any
    if (recentActivity.reviews && recentActivity.reviews.length > 0) {
        hasAnyActivity = true;
        const reviewSection = createReviewSection(recentActivity.reviews);
        timeline.insertBefore(reviewSection, timeline.querySelector('.timeline-item.text-center') || null);
    }
    
    // Add teachers if any
    if (recentActivity.teachers && recentActivity.teachers.length > 0) {
        hasAnyActivity = true;
        const teacherSection = createTeacherSection(recentActivity.teachers);
        timeline.insertBefore(teacherSection, timeline.querySelector('.timeline-item.text-center') || null);
    }
    
    // Add courses if any
    if (recentActivity.courses && recentActivity.courses.length > 0) {
        hasAnyActivity = true;
        const courseSection = createCourseSection(recentActivity.courses);
        timeline.insertBefore(courseSection, timeline.querySelector('.timeline-item.text-center') || null);
    }
    
    // Show no activity message if nothing to display
    if (!hasAnyActivity) {
        const noActivityDiv = document.createElement('div');
        noActivityDiv.className = 'text-center py-5 no-activity-message';
        noActivityDiv.innerHTML = `
            <div class="text-muted">
                <i class="fas fa-history fa-3x mb-3 opacity-50"></i>
                <h5 class="text-muted mb-2">No Recent Activity</h5>
                <p class="mb-0">New enrollments, reviews, teachers, and courses will appear here when available.</p>
            </div>
        `;
        timeline.insertBefore(noActivityDiv, timeline.querySelector('.timeline-item.text-center') || null);
    }
    
    // Update load more button visibility
    updateLoadMoreButton(recentActivity);
}

function createEnrollmentSection(enrollments) {
    const section = document.createElement('div');
    section.className = 'timeline-section';
    section.setAttribute('data-category', 'enrollments');
    
    enrollments.forEach((enrollment, index) => {
        const timelineItem = document.createElement('div');
        timelineItem.className = `timeline-item ${index === 0 ? 'latest' : ''}`;
        
        const hasMore = index < enrollments.length - 1 || document.querySelector('[data-category="teachers"], [data-category="courses"]');
        
        timelineItem.innerHTML = `
            <div class="timeline-marker">
                <div class="timeline-icon bg-primary">
                    <i class="fas fa-user-graduate"></i>
                </div>
                ${hasMore ? '<div class="timeline-line"></div>' : ''}
            </div>
            <div class="timeline-content">
                <div class="timeline-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="timeline-title mb-1">New Enrollment</h6>
                        <span class="timeline-badge badge bg-primary">
                            <i class="fas fa-graduation-cap me-1"></i>Student
                        </span>
                    </div>
                    <p class="timeline-description">
                        <strong>${enrollment.user?.name || 'Student'}</strong> enrolled in 
                        <em>${enrollment.course?.title?.substring(0, 25) || 'Course'}${(enrollment.course?.title?.length || 0) > 25 ? '...' : ''}</em>
                    </p>
                    <div class="timeline-meta">
                        <span class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            ${formatTimeAgo(enrollment.created_at)}
                        </span>
                        <span class="text-muted ms-3">
                            <i class="fas fa-coins me-1"></i>
                            RM${enrollment.course?.price ? parseFloat(enrollment.course.price).toFixed(2) : '0.00'}
                        </span>
                    </div>
                </div>
            </div>
        `;
        
        section.appendChild(timelineItem);
    });
    
    return section;
}

function createReviewSection(reviews) {
    const section = document.createElement('div');
    section.className = 'timeline-section';
    section.setAttribute('data-category', 'reviews');
    
    reviews.forEach((review, index) => {
        const timelineItem = document.createElement('div');
        timelineItem.className = 'timeline-item';
        
        const hasMore = index < reviews.length - 1 || document.querySelector('[data-category="teachers"], [data-category="courses"]');
        
        timelineItem.innerHTML = `
            <div class="timeline-marker">
                <div class="timeline-icon bg-warning">
                    <i class="fas fa-star"></i>
                </div>
                ${hasMore ? '<div class="timeline-line"></div>' : ''}
            </div>
            <div class="timeline-content">
                <div class="timeline-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="timeline-title mb-1">New Review</h6>
                        <span class="timeline-badge badge bg-warning">
                            <i class="fas fa-star me-1"></i>
                            ${review.rating}/5 Stars
                        </span>
                    </div>
                    <p class="timeline-description">
                        <strong>${review.user?.name || 'Student'}</strong> reviewed 
                        <em>${review.course?.title?.substring(0, 25) || 'Course'}${(review.course?.title?.length || 0) > 25 ? '...' : ''}</em>
                        ${review.comment ? `<br><small class="text-muted">"${review.comment.substring(0, 50)}${review.comment.length > 50 ? '...' : ''}"</small>` : ''}
                    </p>
                    <div class="timeline-meta">
                        <span class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            ${formatTimeAgo(review.created_at)}
                        </span>
                        <span class="text-muted ms-3">
                            <i class="fas fa-chalkboard-teacher me-1"></i>
                            ${review.course?.instructor?.name || 'Unknown Instructor'}
                        </span>
                    </div>
                </div>
            </div>
        `;
        
        section.appendChild(timelineItem);
    });
    
    return section;
}

function createTeacherSection(teachers) {
    const section = document.createElement('div');
    section.className = 'timeline-section';
    section.setAttribute('data-category', 'teachers');
    
    teachers.forEach((teacher, index) => {
        const timelineItem = document.createElement('div');
        timelineItem.className = 'timeline-item';
        
        const hasMore = index < teachers.length - 1 || document.querySelector('[data-category="courses"]');
        
        timelineItem.innerHTML = `
            <div class="timeline-marker">
                <div class="timeline-icon bg-success">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                ${hasMore ? '<div class="timeline-line"></div>' : ''}
            </div>
            <div class="timeline-content">
                <div class="timeline-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="timeline-title mb-1">New Teacher</h6>
                        <span class="timeline-badge badge bg-success">
                            <i class="fas fa-user-check me-1"></i>
                            ${teacher.status ? teacher.status.charAt(0).toUpperCase() + teacher.status.slice(1) : 'Active'}
                        </span>
                    </div>
                    <p class="timeline-description">
                        <strong>${teacher.name}</strong> joined as instructor
                        ${teacher.department ? `in <em>${teacher.department}</em>` : ''}
                    </p>
                    <div class="timeline-meta">
                        <span class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            ${formatTimeAgo(teacher.created_at)}
                        </span>
                        ${teacher.courses_count ? `
                        <span class="text-muted ms-3">
                            <i class="fas fa-book me-1"></i>
                            ${teacher.courses_count} courses
                        </span>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        section.appendChild(timelineItem);
    });
    
    return section;
}

function createCourseSection(courses) {
    const section = document.createElement('div');
    section.className = 'timeline-section';
    section.setAttribute('data-category', 'courses');
    
    courses.forEach((course, index) => {
        const timelineItem = document.createElement('div');
        timelineItem.className = 'timeline-item';
        
        const hasMore = index < courses.length - 1;
        
        timelineItem.innerHTML = `
            <div class="timeline-marker">
                <div class="timeline-icon bg-info">
                    <i class="fas fa-book"></i>
                </div>
                ${hasMore ? '<div class="timeline-line"></div>' : ''}
            </div>
            <div class="timeline-content">
                <div class="timeline-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="timeline-title mb-1">New Course</h6>
                        <span class="timeline-badge badge bg-${course.status === 'published' ? 'success' : 'warning'}">
                            <i class="fas fa-${course.status === 'published' ? 'check-circle' : 'clock'} me-1"></i>
                            ${course.status ? course.status.charAt(0).toUpperCase() + course.status.slice(1) : 'Draft'}
                        </span>
                    </div>
                    <p class="timeline-description">
                        <strong>${course.title?.substring(0, 35) || 'Course'}${(course.title?.length || 0) > 35 ? '...' : ''}</strong>
                        <br>
                        <small class="text-muted">
                            by ${course.instructor?.name || 'Unknown Instructor'}
                        </small>
                    </p>
                    <div class="timeline-meta">
                        <span class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            ${formatTimeAgo(course.created_at)}
                        </span>
                        ${course.enrollments_count ? `
                        <span class="text-muted ms-3">
                            <i class="fas fa-users me-1"></i>
                            ${course.enrollments_count} enrolled
                        </span>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        section.appendChild(timelineItem);
    });
    
    return section;
}

function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInMinutes = Math.floor((now - date) / (1000 * 60));
    
    if (diffInMinutes < 1) return 'Just now';
    if (diffInMinutes < 60) return `${diffInMinutes}m ago`;
    
    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours}h ago`;
    
    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 7) return `${diffInDays}d ago`;
    
    return date.toLocaleDateString();
}

function updateLoadMoreButton(recentActivity) {
    const loadMoreButton = document.querySelector('.timeline-item.text-center');
    if (!loadMoreButton) return;
    
    const hasMoreEnrollments = recentActivity.enrollments && recentActivity.enrollments.length >= 5;
    const hasMoreTeachers = recentActivity.teachers && recentActivity.teachers.length >= 5;
    const hasMoreCourses = recentActivity.courses && recentActivity.courses.length >= 5;
    
    const showLoadMore = hasMoreEnrollments || hasMoreTeachers || hasMoreCourses;
    loadMoreButton.style.display = showLoadMore ? 'block' : 'none';
}

function animateValue(element, start, end, duration = 0, suffix = '') {
    // Handle both element ID strings and direct element references
    const targetElement = typeof element === 'string' ? document.getElementById(element) : element;
    if (!targetElement) return;
    
    // Set value immediately without animation
    targetElement.textContent = end.toLocaleString() + suffix;
}

function refreshDashboard() {
    const refreshBtn = document.getElementById('refreshBtn');
    refreshBtn.disabled = true;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Refreshing...';
    
    // Refresh stats
    updateRealtimeStats();
    
    // Refresh chart
    updateChart(currentPeriod);
    
    // Reset button after 2 seconds
    setTimeout(() => {
        refreshBtn.disabled = false;
        refreshBtn.innerHTML = '<i class="fas fa-sync-alt me-1"></i>Refresh';
        showSuccessNotification('Dashboard refreshed successfully');
    }, 2000);
}

function generateReport() {
    showSuccessNotification('Report generation started. You will be notified when ready.');
    
    // Simulate report generation
    setTimeout(() => {
        showSuccessNotification('Performance report has been generated and is ready for download.');
    }, 3000);
}

function clearCache() {
    if (confirm('Are you sure you want to clear the system cache? This may temporarily slow down the application.')) {
        showSuccessNotification('Cache cleared successfully. System performance optimized.');
    }
}

function systemSettings() {
    showSuccessNotification('System settings panel will be available in the next update.');
}

// Timeline Activity Functions
function filterActivity(category) {
    // Prevent event bubbling to avoid dropdown closing
    event.preventDefault();
    event.stopPropagation();
    
    const sections = document.querySelectorAll('.timeline-section');
    const noActivityMessage = document.querySelector('.no-activity-message');
    const noEnrollmentsMessage = document.querySelector('.no-enrollments-message');
    
    // Hide all messages initially
    if (noActivityMessage) noActivityMessage.style.display = 'none';
    if (noEnrollmentsMessage) noEnrollmentsMessage.style.display = 'none';
    
    sections.forEach(section => {
        const sectionCategory = section.getAttribute('data-category');
        
        if (category === 'all' || sectionCategory === category) {
            section.classList.remove('filtered-out');
        } else {
            section.classList.add('filtered-out');
        }
    });
    
    // Special handling for specific filters
    if (category === 'enrollments') {
        const enrollmentSection = document.querySelector('.timeline-section[data-category="enrollments"]');
        const hasEnrollments = enrollmentSection && enrollmentSection.children.length > 0;
        
        if (!hasEnrollments) {
            // Hide all sections and show no enrollments message
            sections.forEach(section => {
                section.classList.add('filtered-out');
            });
            if (noEnrollmentsMessage) {
                noEnrollmentsMessage.style.display = 'block';
            }
        }
    } else if (category === 'reviews') {
        const reviewSection = document.querySelector('.timeline-section[data-category="reviews"]');
        const hasReviews = reviewSection && reviewSection.children.length > 0;
        
        if (!hasReviews) {
            // Hide all sections and show no reviews message
            sections.forEach(section => {
                section.classList.add('filtered-out');
            });
            // Create and show no reviews message if it doesn't exist
            let noReviewsMessage = document.querySelector('.no-reviews-message');
            if (!noReviewsMessage) {
                noReviewsMessage = document.createElement('div');
                noReviewsMessage.className = 'text-center py-5 no-reviews-message';
                noReviewsMessage.innerHTML = `
                    <div class="text-muted">
                        <i class="fas fa-star fa-3x mb-3 opacity-50"></i>
                        <h5 class="text-muted mb-2">No Recent Reviews</h5>
                        <p class="mb-0">When students review courses, their reviews will appear here.</p>
                    </div>
                `;
                const timeline = document.querySelector('.activity-timeline');
                if (timeline) {
                    timeline.appendChild(noReviewsMessage);
                }
            }
            noReviewsMessage.style.display = 'block';
        }
    } else {
        // For other filters, handle normally
        const visibleSections = Array.from(sections).filter(section => 
            !section.classList.contains('filtered-out') && section.children.length > 0
        );
        
        if (visibleSections.length === 0 && noActivityMessage) {
            noActivityMessage.style.display = 'block';
        }
    }
    
    // Update filter button text
    const filterBtn = document.getElementById('activityFilterBtn');
    const categoryNames = {
        'all': 'All Activity',
        'teachers': 'Teachers Only',
        'courses': 'Courses Only',
        'enrollments': 'Enrollments Only',
        'reviews': 'Reviews Only'
    };
    
    if (filterBtn) {
        filterBtn.innerHTML = `<i class="fas fa-filter me-1"></i>${categoryNames[category] || 'Filter'}`;
    }
    
    // Close the dropdown manually
    const dropdown = bootstrap.Dropdown.getInstance(filterBtn);
    if (dropdown) {
        dropdown.hide();
    }
    
    showSuccessNotification(`Filtered to show: ${categoryNames[category]}`);
}

function loadMoreActivity() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
    btn.disabled = true;
    
    // Get current filter to load more of the same type
    const currentFilter = getCurrentFilter();
    
    // Store current scroll position
    const scrollPosition = window.pageYOffset;
    
    // Simulate loading more activity with real data refresh
    setTimeout(() => {
        // Store the filter state before reload
        sessionStorage.setItem('dashboardFilter', currentFilter);
        sessionStorage.setItem('scrollPosition', scrollPosition);
        sessionStorage.setItem('loadMoreClicked', 'true');
        
        // Refresh the page
        location.reload();
    }, 1000);
}

// Helper function to get current filter
function getCurrentFilter() {
    const filterBtn = document.getElementById('activityFilterBtn');
    if (!filterBtn) return 'all';
    
    const filterText = filterBtn.textContent.toLowerCase();
    
    if (filterText.includes('teachers')) return 'teachers';
    if (filterText.includes('courses')) return 'courses';
    if (filterText.includes('enrollments')) return 'enrollments';
    return 'all';
}

// Enhanced notification function
function showSuccessNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'position-fixed top-0 end-0 p-3';
    notification.style.zIndex = '9999';
    
    notification.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" onclick="this.closest('.position-fixed').remove()"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

function showErrorNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'position-fixed top-0 end-0 p-3';
    notification.style.zIndex = '9999';
    
    notification.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" onclick="this.closest('.position-fixed').remove()"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (analyticsChart) {
        analyticsChart.destroy();
    }
});
</script>
@endpush