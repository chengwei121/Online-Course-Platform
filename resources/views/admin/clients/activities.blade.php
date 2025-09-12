@extends('layouts.admin')

@section('title', 'Student Activities - ' . $client->name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/activities.css') }}">

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Student Activities</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients.show', $client) }}">{{ $client->name }}</a></li>
                    <li class="breadcrumb-item active">Activities</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-3 header-buttons">
            <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-info px-4 py-2">
                <i class="fas fa-user me-2"></i> View Profile
            </a>
            <a href="{{ route('admin.clients.enrollments', $client) }}" class="btn btn-success px-4 py-2">
                <i class="fas fa-graduation-cap me-2"></i> View Enrollments
            </a>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="card shadow mb-4 student-info-card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="profile-avatar">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                             style="width: 70px; height: 70px; border: 3px solid #fff; box-shadow: 0 0 0 2px rgba(0,123,255,0.2);">
                            <span class="text-white fw-bold" style="font-size: 1.5rem;">{{ strtoupper(substr($client->name, 0, 2)) }}</span>
                        </div>
                        <div class="online-indicator"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="ms-3">
                        <h4 class="mb-1 fw-bold text-gray-800">{{ $client->name }}</h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-envelope me-2"></i>{{ $client->email }}
                        </p>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2">
                                <i class="fas fa-graduation-cap me-1"></i>Active Student
                            </span>
                            <small class="text-muted">ID: #{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-auto text-end">
                    <div class="d-flex flex-column align-items-end">
                        <div class="text-muted small mb-1">Member since</div>
                        <div class="fw-bold text-gray-800">{{ $client->created_at->format('M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Stats -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.clients.activities', $client) }}" class="row g-3">
                <div class="col-md-4">
                    <label for="filter" class="form-label">Filter by Course Status</label>
                    <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>
                            All Activities ({{ $filterStats['all'] }} courses)
                        </option>
                        <option value="completed" {{ $filter == 'completed' ? 'selected' : '' }}>
                            Completed Courses ({{ $filterStats['completed'] }})
                        </option>
                        <option value="in_progress" {{ $filter == 'in_progress' ? 'selected' : '' }}>
                            In Progress ({{ $filterStats['in_progress'] }})
                        </option>
                        <option value="not_started" {{ $filter == 'not_started' ? 'selected' : '' }}>
                            Not Started ({{ $filterStats['not_started'] }})
                        </option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Course Progress Overview</label>
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="bg-primary text-white rounded p-2">
                                <h5 class="mb-1">{{ $filterStats['all'] }}</h5>
                                <small>Total Courses</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-success text-white rounded p-2">
                                <h5 class="mb-1">{{ $filterStats['completed'] }}</h5>
                                <small>Completed</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-warning text-white rounded p-2">
                                <h5 class="mb-1">{{ $filterStats['in_progress'] }}</h5>
                                <small>In Progress</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-secondary text-white rounded p-2">
                                <h5 class="mb-1">{{ $filterStats['not_started'] }}</h5>
                                <small>Not Started</small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Debug Information (Temporary) -->
    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0">Debug: Real Data Verification</h6>
        </div>
        <div class="card-body">
            <p><strong>Real Course Status Counts:</strong></p>
            <div class="row text-center mb-3">
                <div class="col-3">
                    <div class="bg-primary text-white rounded p-2">
                        <h5 class="mb-0">{{ $filterStats['all'] }}</h5>
                        <small>Total</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="bg-success text-white rounded p-2">
                        <h5 class="mb-0">{{ $filterStats['completed'] }}</h5>
                        <small>Completed</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="bg-warning text-white rounded p-2">
                        <h5 class="mb-0">{{ $filterStats['in_progress'] }}</h5>
                        <small>In Progress</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="bg-secondary text-white rounded p-2">
                        <h5 class="mb-0">{{ $filterStats['not_started'] }}</h5>
                        <small>Not Started</small>
                    </div>
                </div>
            </div>
            
            @if(isset($filterStats['debug']) && count($filterStats['debug']) > 0)
                <details>
                    <summary class="btn btn-outline-info btn-sm">Show Detailed Course Breakdown</summary>
                    <div class="mt-3">
                        @foreach($filterStats['debug'] as $debug)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <div>
                                    <strong>{{ $debug['course'] }}</strong>
                                    <span class="badge bg-{{ $debug['status'] === 'completed' ? 'success' : ($debug['status'] === 'in_progress' ? 'warning' : 'secondary') }} ms-2">
                                        {{ ucfirst(str_replace('_', ' ', $debug['status'])) }}
                                    </span>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted small">{{ $debug['completed_lessons'] }}/{{ $debug['total_lessons'] }} lessons</div>
                                    <div class="text-primary fw-bold">{{ $debug['progress_percentage'] }}%</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </details>
            @endif
        </div>
    </div>

    <!-- Activities -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history me-2"></i>
                Learning Activities Timeline
            </h6>
            <div class="text-muted">
                <small>
                    Showing {{ $pagination['from'] }}-{{ $pagination['to'] }} of {{ $pagination['total'] }} activities
                </small>
            </div>
        </div>
        <div class="card-body">
            @if($activities->count() > 0)
                <div class="row">
                    @foreach($activities as $activity)
                        <div class="col-12 mb-3">
                            <div class="card border-left-{{ $activity['course_status'] === 'completed' ? 'success' : ($activity['course_status'] === 'in_progress' ? 'warning' : 'secondary') }} shadow-sm">
                                <div class="card-body py-2 px-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto pe-2">
                                            @if($activity['type'] === 'enrollment')
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-graduation-cap text-white" style="font-size: 14px;"></i>
                                                </div>
                                            @elseif($activity['status'] === 'completed')
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-check text-white" style="font-size: 14px;"></i>
                                                </div>
                                            @else
                                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-play text-white" style="font-size: 14px;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1">
                                                {{ $activity['title'] }}
                                                @if($activity['status'] === 'completed')
                                                    <span class="badge bg-success ms-2">Completed</span>
                                                @elseif($activity['status'] === 'enrolled')
                                                    <span class="badge bg-primary ms-2">Enrolled</span>
                                                @else
                                                    <span class="badge bg-warning ms-2">In Progress</span>
                                                @endif
                                            </h6>
                                            <p class="mb-1 text-muted small">{{ $activity['description'] }}</p>
                                            
                                            <!-- Real-time Progress Bar -->
                                            <div class="mt-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <small class="text-muted">Course Progress</small>
                                                    <span class="badge bg-{{ $activity['course_status'] === 'completed' ? 'success' : ($activity['course_status'] === 'in_progress' ? 'warning' : 'secondary') }}">
                                                        {{ $activity['progress_percentage'] }}%
                                                    </span>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-{{ $activity['course_status'] === 'completed' ? 'success' : 'primary' }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $activity['progress_percentage'] }}%"
                                                         data-course-id="{{ $activity['course_id'] }}"
                                                         data-toggle="tooltip" 
                                                         title="{{ $activity['completed_lessons'] }}/{{ $activity['total_lessons'] }} lessons completed">
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $activity['completed_lessons'] }}/{{ $activity['total_lessons'] }} lessons
                                                    • Status: 
                                                    <span class="text-{{ $activity['course_status'] === 'completed' ? 'success' : ($activity['course_status'] === 'in_progress' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $activity['course_status'])) }}
                                                    </span>
                                                    <small class="text-muted">({{ $activity['progress_percentage'] }}%)</small>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">
                                                <i class="fas fa-book me-1"></i>
                                                {{ Str::limit($activity['course'], 25) }}
                                            </small>
                                            <div class="mt-1">
                                                @if($activity['course_status'] === 'completed')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-trophy me-1"></i>Course Complete
                                                    </span>
                                                @elseif($activity['course_status'] === 'in_progress')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>{{ $activity['total_lessons'] - $activity['completed_lessons'] }} lessons left
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-play me-1"></i>Ready to start
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto text-end">
                                            <small class="text-muted">
                                                {{ $activity['date']->format('M d, Y') }}<br>
                                                {{ $activity['date']->format('H:i A') }}
                                            </small>
                                            <div class="mt-1">
                                                <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Enhanced Pagination -->
                @if($pagination['last_page'] > 1)
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 p-3 pagination-container">
                        <!-- Page Info -->
                        <div class="mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge page-info-badge fs-6 px-3 py-2">
                                        Page {{ $pagination['current_page'] }} of {{ $pagination['last_page'] }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Showing {{ $pagination['from'] }}-{{ $pagination['to'] }} of {{ $pagination['total'] }} activities
                                </small>
                            </div>
                        </div>

                        <!-- Pagination Controls -->
                        <nav aria-label="Activities pagination">
                            <div class="btn-toolbar" role="toolbar">
                                <!-- First and Previous -->
                                <div class="btn-group me-2" role="group">
                                    @if($pagination['current_page'] > 1)
                                        <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}" 
                                           class="btn btn-outline-primary btn-sm" 
                                           data-bs-toggle="tooltip" title="First Page">
                                            <i class="fas fa-angle-double-left"></i>
                                        </a>
                                        <a href="{{ $pagination['prev_page_url'] }}" 
                                           class="btn btn-outline-primary btn-sm"
                                           data-bs-toggle="tooltip" title="Previous Page">
                                            <i class="fas fa-angle-left"></i> Prev
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="fas fa-angle-double-left"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="fas fa-angle-left"></i> Prev
                                        </button>
                                    @endif
                                </div>

                                <!-- Page Numbers -->
                                <div class="btn-group me-2" role="group">
                                    @php
                                        $start = max(1, $pagination['current_page'] - 2);
                                        $end = min($pagination['last_page'], $pagination['current_page'] + 2);
                                    @endphp

                                    @if($start > 1)
                                        <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}" 
                                           class="btn btn-outline-primary btn-sm">1</a>
                                        @if($start > 2)
                                            <button class="btn btn-outline-secondary btn-sm" disabled>...</button>
                                        @endif
                                    @endif

                                    @for($i = $start; $i <= $end; $i++)
                                        @if($i == $pagination['current_page'])
                                            <button class="btn btn-primary btn-sm">{{ $i }}</button>
                                        @else
                                            <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" 
                                               class="btn btn-outline-primary btn-sm">{{ $i }}</a>
                                        @endif
                                    @endfor

                                    @if($end < $pagination['last_page'])
                                        @if($end < $pagination['last_page'] - 1)
                                            <button class="btn btn-outline-secondary btn-sm" disabled>...</button>
                                        @endif
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['last_page']]) }}" 
                                           class="btn btn-outline-primary btn-sm">{{ $pagination['last_page'] }}</a>
                                    @endif
                                </div>

                                <!-- Next and Last -->
                                <div class="btn-group" role="group">
                                    @if($pagination['current_page'] < $pagination['last_page'])
                                        <a href="{{ $pagination['next_page_url'] }}" 
                                           class="btn btn-outline-primary btn-sm"
                                           data-bs-toggle="tooltip" title="Next Page">
                                            Next <i class="fas fa-angle-right"></i>
                                        </a>
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['last_page']]) }}" 
                                           class="btn btn-outline-primary btn-sm"
                                           data-bs-toggle="tooltip" title="Last Page">
                                            <i class="fas fa-angle-double-right"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>
                                            Next <i class="fas fa-angle-right"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="fas fa-angle-double-right"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </nav>

                        <!-- Quick Jump (for larger pagination) -->
                        @if($pagination['last_page'] > 10)
                            <div class="mt-3 mt-md-0">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <span class="input-group-text">Go to</span>
                                    <input type="number" class="form-control" id="pageJump" 
                                           min="1" max="{{ $pagination['last_page'] }}" 
                                           placeholder="Page"
                                           onkeypress="if(event.key==='Enter') jumpToPage()">
                                    <button class="btn btn-outline-primary" onclick="jumpToPage()">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- JavaScript for page jumping -->
                    @if($pagination['last_page'] > 10)
                        <script>
                            function jumpToPage() {
                                const pageInput = document.getElementById('pageJump');
                                const pageNumber = parseInt(pageInput.value);
                                if (pageNumber >= 1 && pageNumber <= {{ $pagination['last_page'] }}) {
                                    const currentUrl = new URL(window.location.href);
                                    currentUrl.searchParams.set('page', pageNumber);
                                    window.location.href = currentUrl.toString();
                                } else {
                                    alert('Please enter a valid page number between 1 and {{ $pagination['last_page'] }}');
                                }
                            }
                        </script>
                    @endif
                    
                    <!-- Initialize Bootstrap tooltips -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Initialize tooltips
                            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl);
                            });
                        });
                    </script>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h5>No Activities Yet</h5>
                    <p class="text-muted">This student hasn't started any learning activities.</p>
                    <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Profile
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15) !important;
}

.pagination-sm .page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.7rem;
}

@media (max-width: 768px) {
    .card-body .row {
        text-align: center;
    }
    
    .card-body .col-md-1,
    .card-body .col-md-2,
    .card-body .col-md-3,
    .card-body .col-md-6 {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush
@endsection
