@extends('layouts.admin')

@section('title', 'Edit Student - ' . $client->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Student</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients.show', $client) }}">{{ $client->name }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit me-2"></i>
                        Edit Student Information
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.clients.update', $client) }}">
                        @csrf
                        @method('PUT')

                        <!-- Student Info Display -->
                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px;">
                                    <span class="text-white h3 mb-0">{{ substr($client->name, 0, 2) }}</span>
                                </div>
                                <div class="text-muted">
                                    <small>Student ID: #{{ $client->id }}</small><br>
                                    <small>Joined: {{ $client->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Admin Edit Mode:</strong> You can modify this student's basic information. 
                                    Only change the password if the student has requested it.
                                </div>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-3 col-form-label text-md-end">
                                <strong>Full Name <span class="text-danger">*</span></strong>
                            </label>
                            <div class="col-md-9">
                                <input id="name" type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $client->name) }}" 
                                       required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-3 col-form-label text-md-end">
                                <strong>Email Address <span class="text-danger">*</span></strong>
                            </label>
                            <div class="col-md-9">
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email', $client->email) }}" 
                                       required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">
                                    @if($client->email_verified_at)
                                        <span class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Email verified on {{ $client->email_verified_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Email not verified
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Password Section -->
                        <div class="row mb-3">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <h6 class="text-muted">
                                    <i class="fas fa-lock me-2"></i>
                                    Password Settings
                                </h6>
                                <p class="text-muted small">
                                    Leave password fields empty to keep the current password unchanged.
                                    Only update if the student has specifically requested a password reset.
                                </p>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="row mb-3">
                            <label for="password" class="col-md-3 col-form-label text-md-end">
                                <strong>New Password</strong>
                            </label>
                            <div class="col-md-9">
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">
                                    Password must be at least 8 characters long.
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-3 col-form-label text-md-end">
                                <strong>Confirm Password</strong>
                            </label>
                            <div class="col-md-9">
                                <input id="password-confirm" type="password" 
                                       class="form-control" 
                                       name="password_confirmation" autocomplete="new-password">
                                <div class="form-text">
                                    Confirm the new password if you entered one above.
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Student Stats Display -->
                        <div class="row mb-4">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <div class="row text-center">
                                    <div class="col-3">
                                        <div class="border-end">
                                            <h5 class="text-primary mb-1">{{ $client->enrollments->count() }}</h5>
                                            <small class="text-muted">Enrollments</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="border-end">
                                            <h5 class="text-success mb-1">
                                                RM{{ number_format($client->enrollments->sum(function($e) { 
                                                    return $e->course->is_free ? 0 : $e->course->price; 
                                                }), 2) }}
                                            </h5>
                                            <small class="text-muted">Total Spent</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="border-end">
                                            <h5 class="text-warning mb-1">{{ $client->lessonProgress->where('completed', true)->count() }}</h5>
                                            <small class="text-muted">Lessons Done</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <h5 class="text-info mb-1">{{ $client->updated_at->diffForHumans() }}</h5>
                                        <small class="text-muted">Last Active</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mb-0">
                            <div class="col-md-9 offset-md-3">
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i>
                                        Update Student
                                    </button>
                                    <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
                                    </a>
                                    <a href="{{ route('admin.clients.activities', $client) }}" class="btn btn-info">
                                        <i class="fas fa-history me-2"></i>
                                        View Activities
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
