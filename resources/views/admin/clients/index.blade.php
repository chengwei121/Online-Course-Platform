@extends('layouts.admin')

@section('title', 'Students Management')

@section('content')
<div data-page-loaded="true">
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Students Management</h1>
        <div class="text-muted">
            <i class="fas fa-info-circle me-2"></i>
            View and edit student information
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.clients.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search students..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Students</option>
                        <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>With Enrollments</option>
                        <option value="not_enrolled" {{ request('status') == 'not_enrolled' ? 'selected' : '' }}>No Enrollments</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Students ({{ $clients->total() }})</h6>
        </div>
        <div class="card-body">
            @if($clients->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Email</th>
                                <th>Enrollments</th>
                                <th>Total Spent</th>
                                <th>Join Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <span class="text-white fw-bold">{{ substr($client->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $client->name }}</div>
                                                <small class="text-muted">ID: {{ $client->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $client->email }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $client->enrollments->count() }}</span>
                                        @if($client->enrollments->count() > 0)
                                            <br>
                                            <small class="text-muted">
                                                <a href="{{ route('admin.clients.enrollments', $client) }}" class="text-decoration-none">
                                                    View details
                                                </a>
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        RM{{ number_format($client->enrollments->sum(function($enrollment) {
                                            return $enrollment->course->is_free ? 0 : $enrollment->course->price;
                                        }), 2) }}
                                    </td>
                                    <td>{{ $client->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.clients.show', $client) }}" 
                                               class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.clients.edit', $client) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.clients.activities', $client) }}" 
                                               class="btn btn-success btn-sm" title="View Activities">
                                                <i class="fas fa-history"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $clients->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5>No students found</h5>
                    <p class="text-muted">No students match your current filters.</p>
                    <p class="text-info">Students can register through the main website.</p>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
