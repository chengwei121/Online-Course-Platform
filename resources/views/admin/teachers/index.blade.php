@extends('layouts.admin')

@section('title', 'Teachers Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-chalkboard-teacher me-2"></i>Teachers Management
    </h1>
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>Add New Teacher
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Teachers
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Active Teachers
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Inactive Teachers
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-times fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            With Courses
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['with_courses'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-filter me-2"></i>Teachers List
        </h6>
        <small class="text-muted">Showing {{ $teachers->firstItem() ?? 0 }} to {{ $teachers->lastItem() ?? 0 }} of {{ $teachers->total() }} results</small>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.teachers.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label text-muted small">
                    <i class="fas fa-search me-1"></i>Search
                </label>
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Search by name, email, department..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">
                    <i class="fas fa-toggle-on me-1"></i>Status
                </label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">
                    <i class="fas fa-building me-1"></i>Department
                </label>
                <select name="department" class="form-select">
                    <option value="">All Departments</option>
                    <option value="Information Technology" {{ request('department') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                    <option value="Computer Science" {{ request('department') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                    <option value="Software Engineering" {{ request('department') == 'Software Engineering' ? 'selected' : '' }}>Software Engineering</option>
                    <option value="Information Systems" {{ request('department') == 'Information Systems' ? 'selected' : '' }}>Information Systems</option>
                    <option value="Computer Engineering" {{ request('department') == 'Computer Engineering' ? 'selected' : '' }}>Computer Engineering</option>
                    <option value="Cybersecurity" {{ request('department') == 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                    <option value="Data Science" {{ request('department') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                    <option value="Artificial Intelligence" {{ request('department') == 'Artificial Intelligence' ? 'selected' : '' }}>Artificial Intelligence</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </div>
        </form>

        <!-- Teachers Table -->
        @if($teachers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 18%">Teacher</th>
                            <th style="width: 13%">Department</th>
                            <th style="width: 12%">Qualification</th>
                            <th style="width: 10%">Hourly Rate</th>
                            <th style="width: 8%">Courses</th>
                            <th style="width: 9%">Status</th>
                            <th style="width: 10%">Join Date</th>
                            <th style="width: 15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                            <tr>
                                <td class="text-center">{{ $teacher->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($teacher->profile_picture)
                                            <img src="{{ Storage::url($teacher->profile_picture) }}" 
                                                 alt="{{ $teacher->user->name }}"
                                                 class="rounded-circle me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 40px; height: 40px;">
                                                <span class="text-white fw-bold">{{ substr($teacher->user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $teacher->user->name }}</div>
                                            <small class="text-muted">{{ $teacher->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $teacher->department }}</span>
                                </td>
                                <td>{{ $teacher->qualification }}</td>
                                <td class="text-center">
                                    @if($teacher->hourly_rate)
                                        <span class="badge bg-success">RM {{ number_format($teacher->hourly_rate, 2) }}</span>
                                    @else
                                        <span class="badge bg-secondary">Not Set</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $teacher->courses_count }}</span>
                                </td>
                                <td>
                                    @if($teacher->status == 'active')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-pause-circle me-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.teachers.show', $teacher) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                                           class="btn btn-warning btn-sm"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.teachers.destroy', $teacher) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this teacher? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $teachers->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No teachers found</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'status', 'department']))
                        No teachers match your current filters.
                    @else
                        Start by adding your first teacher.
                    @endif
                </p>
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-1"></i>Add New Teacher
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
