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
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-left: 4px solid;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
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
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem;
        transition: border-color 0.2s ease;
    }

    .form-control:focus {
        border-color: #2c3e50;
        box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-clear {
        background: #f7fafc;
        color: #4a5568;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-clear:hover {
        background: #edf2f7;
        border-color: #cbd5e0;
    }

    .students-table {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #2d3748, #4a5568);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background: #f7fafc;
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #2d3748;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .table td {
        padding: 1rem;
        border-top: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        margin-right: 0.75rem;
    }

    .student-info {
        display: flex;
        align-items: center;
    }

    .student-details h6 {
        margin: 0;
        font-weight: 600;
        color: #2d3748;
    }

    .student-details p {
        margin: 0;
        font-size: 0.875rem;
        color: #718096;
    }

    .enrollment-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .enrollment-badge.active {
        background: #c6f6d5;
        color: #22543d;
    }

    .enrollment-badge.completed {
        background: #bee3f8;
        color: #2a4365;
    }

    .enrollment-badge.pending {
        background: #fefcbf;
        color: #744210;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-right: 0.5rem;
    }

    .btn-view {
        background: #e6fffa;
        color: #234e52;
        border: 1px solid #81e6d9;
    }

    .btn-view:hover {
        background: #b2f5ea;
        color: #234e52;
        text-decoration: none;
    }

    .btn-message {
        background: #fef5e7;
        color: #744210;
        border: 1px solid #f6e05e;
    }

    .btn-message:hover {
        background: #fed7aa;
        color: #744210;
        text-decoration: none;
    }

    .no-students {
        text-align: center;
        padding: 4rem 2rem;
        color: #718096;
    }

    .no-students i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #cbd5e0;
    }

    .pagination-wrapper {
        background: white;
        padding: 1.5rem;
        border-radius: 0 0 12px 12px;
        border-top: 1px solid #e2e8f0;
    }

    .pagination-info {
        color: #718096;
        font-size: 0.875rem;
    }

    .pagination {
        margin: 0;
        justify-content: center;
    }

    .pagination .page-item {
        margin: 0 0.125rem;
    }

    .pagination .page-link {
        border: 1px solid #e2e8f0;
        color: #4a5568;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        background: white;
    }

    .pagination .page-link:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
        color: #2c3e50;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(44, 62, 80, 0.1);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        border-color: #2c3e50;
        color: white;
        box-shadow: 0 2px 8px rgba(44, 62, 80, 0.3);
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

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
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
            <form method="GET" action="{{ route('teacher.students.index') }}">
                <div class="filter-grid">
                    <div class="form-group">
                        <label class="form-label">Search Students</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name or email..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Filter by Course</label>
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
                        <label class="form-label">Enrollment Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort By</label>
                        <select name="sort_by" class="form-control">
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="joined" {{ request('sort_by') == 'joined' ? 'selected' : '' }}>Join Date</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-filter">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <a href="{{ route('teacher.students.index') }}" class="btn btn-clear">
                                <i class="fas fa-times me-2"></i>Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Students Table -->
        <div class="students-table">
            <div class="table-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Students List ({{ $students->total() }} total)
                </h5>
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
                                            @if($student->avatar && file_exists(public_path($student->avatar)))
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
                                                @if($student->phone)
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
                                            <span class="enrollment-badge active">{{ $activeEnrollments }} Active</span>
                                        @endif
                                        
                                        @if($completedEnrollments > 0)
                                            <span class="enrollment-badge completed">{{ $completedEnrollments }} Completed</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->created_at->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('teacher.students.show', $student->id) }}" 
                                           class="btn-action btn-view">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                        <a href="#" class="btn-action btn-message" 
                                           onclick="openMessageModal({{ $student->id }}, '{{ $student->name }}')">
                                            <i class="fas fa-envelope me-1"></i>Message
                                        </a>
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
                                <small class="text-muted">
                                    Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
                                </small>
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

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="messageForm">
                    <input type="hidden" id="studentId" name="student_id">
                    <div class="mb-3">
                        <label class="form-label">To: <span id="studentName"></span></label>
                    </div>
                    <div class="mb-3">
                        <label for="messageText" class="form-label">Message</label>
                        <textarea class="form-control" id="messageText" name="message" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendMessage()">Send Message</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openMessageModal(studentId, studentName) {
    document.getElementById('studentId').value = studentId;
    document.getElementById('studentName').textContent = studentName;
    document.getElementById('messageText').value = '';
    
    var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
    messageModal.show();
}

function sendMessage() {
    const studentId = document.getElementById('studentId').value;
    const message = document.getElementById('messageText').value;
    
    if (!message.trim()) {
        alert('Please enter a message.');
        return;
    }
    
    // Here you would typically send an AJAX request
    // For now, just show a placeholder message
    alert('Message functionality will be implemented soon.');
    
    var messageModal = bootstrap.Modal.getInstance(document.getElementById('messageModal'));
    messageModal.hide();
}
</script>
@endpush