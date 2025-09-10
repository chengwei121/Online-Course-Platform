@extends('layouts.admin')

@section('title', 'Students Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Students Management</h1>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Student
        </a>
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
                                        ${{ number_format($client->enrollments->sum(function($enrollment) {
                                            return $enrollment->course->is_free ? 0 : $enrollment->course->price;
                                        }), 2) }}
                                    </td>
                                    <td>{{ $client->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.clients.show', $client) }}" 
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.clients.edit', $client) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($client->enrollments->count() == 0)
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm" 
                                                        onclick="confirmDelete('{{ $client->id }}', '{{ $client->name }}')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
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
                    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">Add First Student</a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the student "<span id="clientName"></span>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Student</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(clientId, clientName) {
    document.getElementById('clientName').textContent = clientName;
    document.getElementById('deleteForm').action = `/admin/clients/${clientId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection
