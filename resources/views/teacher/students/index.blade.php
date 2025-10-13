@extends('teacher.layouts.app')

@section('title', 'My Students')

@push('styles')
<style>
    .students-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .students-header {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .stat-card {
        background: white;
        padding: 1.75rem 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-left: 5px solid;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        opacity: 0.05;
        transition: all 0.3s ease;
    }

    .stat-card.total::before { background: #2c3e50; }
    .stat-card.active::before { background: #3498db; }
    .stat-card.completed::before { background: #27ae60; }
    .stat-card.courses::before { background: #34495e; }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    }

    .stat-card:hover::before {
        transform: scale(1.2);
        opacity: 0.08;
    }

    .stat-card.total { border-left-color: #2c3e50; }
    .stat-card.active { border-left-color: #3498db; }
    .stat-card.completed { border-left-color: #27ae60; }
    .stat-card.courses { border-left-color: #34495e; }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card.total .stat-icon { background: linear-gradient(135deg, #2c3e50, #34495e); }
    .stat-card.active .stat-icon { background: linear-gradient(135deg, #3498db, #2980b9); }
    .stat-card.completed .stat-icon { background: linear-gradient(135deg, #27ae60, #229954); }
    .stat-card.courses .stat-icon { background: linear-gradient(135deg, #34495e, #2c3e50); }

    .filter-section {
        background: white;
        padding: 1.75rem;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 1.75rem;
        border: 1px solid #f0f0f0;
    }

    .filter-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .filter-header i {
        font-size: 1.25rem;
        color: #2c3e50;
        margin-right: 0.75rem;
    }

    .filter-header h5 {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
        font-size: 1.125rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1.5fr 1.5fr 1.5fr;
        gap: 1.25rem;
        align-items: end;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        margin-right: 0.5rem;
        color: #6b7280;
        font-size: 0.8125rem;
    }

    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        font-size: 0.9375rem;
    }

    .form-control:focus {
        border-color: #2c3e50;
        box-shadow: 0 0 0 4px rgba(44, 62, 80, 0.1);
        outline: none;
    }

    .form-control:hover {
        border-color: #cbd5e1;
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-filter {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        border: none;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2);
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(44, 62, 80, 0.3);
        background: linear-gradient(135deg, #34495e, #2c3e50);
    }

    .btn-clear {
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-clear:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #374151;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .students-table {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }

    .table-header {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        padding: 1.25rem 1.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.125rem;
    }

    .table-header i {
        font-size: 1.25rem;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background: linear-gradient(180deg, #f9fafb, #f3f4f6);
        border: none;
        padding: 1.125rem 1.25rem;
        font-weight: 600;
        color: #374151;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.075em;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f9fafb;
        transform: scale(1.005);
    }

    .table td {
        padding: 1.125rem 1.25rem;
        border-top: 1px solid #f0f0f0;
        vertical-align: middle;
        font-size: 0.9375rem;
        color: #374151;
    }

    .student-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        object-fit: cover;
        margin-right: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .student-avatar-placeholder {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.875rem;
        margin-right: 1rem;
        box-shadow: 0 2px 8px rgba(44, 62, 80, 0.3);
    }

    .student-info {
        display: flex;
        align-items: center;
    }

    .student-details h6 {
        margin: 0 0 0.25rem 0;
        font-weight: 600;
        color: #2d3748;
        font-size: 0.9375rem;
    }

    .student-details p {
        margin: 0;
        font-size: 0.8125rem;
        color: #718096;
    }

    .student-details p i {
        opacity: 0.7;
        font-size: 0.75rem;
    }

    .enrollment-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .enrollment-badge.active {
        background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
        color: #1a5928;
    }

    .enrollment-badge.completed {
        background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
        color: #1e4d8b;
    }

    .enrollment-badge.pending {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        color: #8b4513;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.625rem 1.125rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-view {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        box-shadow: 0 4px 10px rgba(44, 62, 80, 0.3);
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(44, 62, 80, 0.4);
        background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
        color: white;
        text-decoration: none;
    }

    .no-students {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .no-students i {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        color: #e2e8f0;
        display: block;
    }

    .no-students h4 {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 1.5rem;
    }

    .no-students p {
        color: #718096;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .pagination-wrapper {
        background: linear-gradient(180deg, #ffffff, #f9fafb);
        padding: 1.75rem 2rem;
        border-radius: 0 0 16px 16px;
        border-top: 2px solid #f0f0f0;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 0.9375rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-info i {
        color: #9ca3af;
    }

    .pagination {
        margin: 0;
        justify-content: center;
    }

    .pagination .page-item {
        margin: 0 0.25rem;
    }

    .pagination .page-link {
        border: 2px solid #e5e7eb;
        color: #4b5563;
        padding: 0.625rem 0.875rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
        background: white;
        min-width: 42px;
        text-align: center;
    }

    .pagination .page-link:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
        color: #2c3e50;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .pagination .page-item.active .page-link:hover {
        background: linear-gradient(135deg, #34495e, #2c3e50);
        transform: translateY(-1px);
    }

    .pagination .page-item.disabled .page-link {
        color: #a0aec0;
        background-color: #f7fafc;
        border-color: #e2e8f0;
        cursor: not-allowed;
    }

    .pagination .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: none;
    }

    .sort-link {
        color: #4a5568;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .sort-link:hover {
        color: #2d3748;
        text-decoration: none;
    }

    .sort-link.active {
        color: #2c3e50;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .students-container {
            padding: 1rem 0;
        }

        .students-header {
            padding: 1.5rem 0;
            margin-bottom: 1rem;
        }

        .students-header h1 {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            padding: 1.5rem;
        }

        .filter-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .filter-actions {
            flex-direction: column;
        }

        .btn-filter,
        .btn-clear {
            width: 100%;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.375rem;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .filter-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .table-responsive {
            margin: 0 -0.5rem;
        }

        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
            margin-bottom: 0.25rem;
        }

        .pagination-wrapper {
            padding: 1rem;
        }

        .pagination-wrapper .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .pagination-info {
            order: 2;
        }

        .pagination {
            order: 1;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination .page-item {
            margin: 0.125rem;
        }

        .pagination .page-link {
            padding: 0.375rem 0.5rem;
            font-size: 0.8125rem;
            min-width: 40px;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="students-container">
    <!-- Header -->
    <div class="students-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="fas fa-users me-3"></i>
                        My Students
                    </h1>
                    <p class="mb-0 opacity-90">Manage and track your students' progress across all your courses</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <!-- Export functionality removed -->
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="mb-1">{{ $stats['total_students'] }}</h3>
                <p class="text-muted mb-0">Total Students</p>
            </div>
            <div class="stat-card active">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <h3 class="mb-1">{{ $stats['active_enrollments'] }}</h3>
                <p class="text-muted mb-0">Active Enrollments</p>
            </div>
            <div class="stat-card completed">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="mb-1">{{ $stats['completed_enrollments'] }}</h3>
                <p class="text-muted mb-0">Completed Courses</p>
            </div>
            <div class="stat-card courses">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="mb-1">{{ $stats['total_courses'] }}</h3>
                <p class="text-muted mb-0">Your Courses</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <div class="filter-header">
                <i class="fas fa-sliders-h"></i>
                <h5>Filter Students</h5>
            </div>
            <form method="GET" action="{{ route('teacher.students.index') }}">
                <div class="filter-grid">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-search"></i>
                            Search Students
                        </label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name or email..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-book"></i>
                            Course
                        </label>
                        <select name="course_id" class="form-control">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" 
                                        {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-check"></i>
                            Status
                        </label>
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-sort"></i>
                            Sort By
                        </label>
                        <select name="sort_by" class="form-control">
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="joined" {{ request('sort_by') == 'joined' ? 'selected' : '' }}>Join Date</option>
                        </select>
                    </div>
                </div>
                <div class="filter-actions" style="margin-top: 1.25rem;">
                    <button type="submit" class="btn btn-filter">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                    <a href="{{ route('teacher.students.index') }}" class="btn btn-clear">
                        <i class="fas fa-redo"></i> Reset Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Students Table -->
        <div class="students-table">
            <div class="table-header">
                <i class="fas fa-table"></i>
                <span>Students List</span>
                <span style="margin-left: auto; font-size: 0.875rem; opacity: 0.9;">
                    <i class="fas fa-users" style="font-size: 0.875rem;"></i>
                    {{ $students->total() }} total students
                </span>
            </div>

            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="sort-link {{ request('sort_by') == 'name' ? 'active' : '' }}">
                                        Student
                                        @if(request('sort_by') == 'name')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="sort-link {{ request('sort_by') == 'email' ? 'active' : '' }}">
                                        Email
                                        @if(request('sort_by') == 'email')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Enrolled Courses</th>
                                <th>Status</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'joined', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="sort-link {{ request('sort_by') == 'joined' ? 'active' : '' }}">
                                        Joined
                                        @if(request('sort_by') == 'joined')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            @if(isset($student->avatar) && $student->avatar && @file_exists(public_path($student->avatar)))
                                                <img src="{{ asset($student->avatar) }}" 
                                                     alt="{{ $student->name }}" 
                                                     class="student-avatar"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}'">
                                            @else
                                                <div class="student-avatar-placeholder">
                                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div class="student-details">
                                                <h6>{{ $student->name }}</h6>
                                                @if(isset($student->phone) && $student->phone)
                                                    <p><i class="fas fa-phone me-1"></i>{{ $student->phone }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $student->enrollments->count() }} courses</span>
                                    </td>
                                    <td>
                                        @php
                                            $activeEnrollments = $student->enrollments->where('status', 'active')->count();
                                            $completedEnrollments = $student->enrollments->where('status', 'completed')->count();
                                        @endphp
                                        
                                        @if($activeEnrollments > 0)
                                            <span class="enrollment-badge active">
                                                <i class="fas fa-circle"></i>
                                                {{ $activeEnrollments }} Active
                                            </span>
                                        @endif
                                        
                                        @if($completedEnrollments > 0)
                                            <span class="enrollment-badge completed">
                                                <i class="fas fa-check-circle"></i>
                                                {{ $completedEnrollments }} Completed
                                            </span>
                                        @endif

                                        @if($activeEnrollments == 0 && $completedEnrollments == 0)
                                            <span class="enrollment-badge pending">
                                                <i class="fas fa-clock"></i>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt" style="color: #9ca3af; font-size: 0.875rem;"></i>
                                        {{ \Carbon\Carbon::parse($student->created_at)->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('teacher.students.show', $student->id) }}" 
                                               class="btn-action btn-view">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    @if($students->hasPages())
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="pagination-info">
                                <i class="fas fa-info-circle"></i>
                                <span>
                                    Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
                                </span>
                            </div>
                            
                            <nav aria-label="Students pagination">
                                <ul class="pagination mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($students->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $students->previousPageUrl() }}" rel="prev">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @php
                                        $start = max(1, $students->currentPage() - 2);
                                        $end = min($students->lastPage(), $students->currentPage() + 2);
                                    @endphp

                                    {{-- First Page --}}
                                    @if($start > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $students->url(1) }}">1</a>
                                        </li>
                                        @if($start > 2)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for($i = $start; $i <= $end; $i++)
                                        @if ($i == $students->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $i }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $students->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Last Page --}}
                                    @if($end < $students->lastPage())
                                        @if($end < $students->lastPage() - 1)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $students->url($students->lastPage()) }}">{{ $students->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($students->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $students->nextPageUrl() }}" rel="next">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            @else
                <div class="no-students">
                    <i class="fas fa-users"></i>
                    <h4>No Students Found</h4>
                    <p>No students are currently enrolled in your courses or match your search criteria.</p>
                    @if(request()->hasAny(['search', 'course_id', 'status']))
                        <a href="{{ route('teacher.students.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// No additional scripts needed
</script>
@endpush