@extends('layouts.admin')

@section('title', 'Teachers Management')

@section('header')
    <h1 class="h2">
        <i class="fas fa-chalkboard-teacher me-2"></i>
        Teachers Management
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Add New Teacher
            </a>
        </div>
    </div>
@endsection

@section('content')
<div data-page-loaded="true">
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list me-2"></i>
            All Teachers ({{ $teachers->total() }})
        </h6>
    </div>
    <div class="card-body">
        @if($teachers->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Qualification</th>
                            <th>Courses</th>
                            <th>Status</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                            <tr>
                                <td class="text-center">
                                    @if($teacher->profile_picture)
                                        <img src="{{ Storage::url($teacher->profile_picture) }}" 
                                             class="rounded-circle" width="50" height="50" alt="Profile">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $teacher->name }}</strong>
                                        @if($teacher->phone)
                                            <br><small class="text-muted">{{ $teacher->phone }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $teacher->email }}</td>
                                <td>{{ $teacher->department }}</td>
                                <td>{{ $teacher->qualification }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $teacher->courses_count }} courses</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($teacher->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.teachers.show', $teacher) }}" 
                                           class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.teachers.toggle-status', $teacher) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-outline-{{ $teacher->status === 'active' ? 'warning' : 'success' }}" 
                                                    title="{{ $teacher->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $teacher->status === 'active' ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        @if($teacher->courses_count == 0)
                                            <form action="{{ route('admin.teachers.destroy', $teacher) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
                {{ $teachers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-user-plus fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">No Teachers Found</h4>
                <p class="text-muted mb-4">Get started by adding your first teacher to the platform.</p>
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>Add First Teacher
                </a>
            </div>
        @endif
    </div>
</div>
</div>
@endsection
