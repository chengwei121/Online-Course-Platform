@extends('teacher.layouts.app')

@section('title', 'Edit Assignment - ' . $assignment->title)
@section('page-title', 'Edit Assignment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}">{{ Str::limit($course->title, 30) }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.lessons.index', $course) }}">Lessons</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}">{{ Str::limit($lesson->title, 30) }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.assignments.show', [$course, $lesson, $assignment]) }}">{{ Str::limit($assignment->title, 30) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-edit me-2"></i>Edit Assignment
                    </h1>
                    <p class="text-muted mb-0">For lesson: <strong>{{ $lesson->title }}</strong></p>
                </div>
                <a href="{{ route('teacher.assignments.show', [$course, $lesson, $assignment]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Cancel
                </a>
            </div>

            <!-- Edit Form -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('teacher.assignments.update', [$course, $lesson, $assignment]) }}" method="POST" id="editAssignmentForm">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-1"></i>Assignment Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $assignment->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      required>{{ old('description', $assignment->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Instructions -->
                        <div class="mb-4">
                            <label for="instructions" class="form-label">
                                <i class="fas fa-list-ol me-1"></i>Instructions <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                      id="instructions" 
                                      name="instructions" 
                                      rows="6"
                                      required>{{ old('instructions', $assignment->instructions) }}</textarea>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Assignment Type -->
                            <div class="col-md-6 mb-4">
                                <label for="assignment_type" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Assignment Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('assignment_type') is-invalid @enderror" 
                                        id="assignment_type" 
                                        name="assignment_type" 
                                        required>
                                    <option value="">Select Type</option>
                                    <option value="quiz" {{ old('assignment_type', $assignment->assignment_type) == 'quiz' ? 'selected' : '' }}>
                                        📝 Quiz
                                    </option>
                                    <option value="project" {{ old('assignment_type', $assignment->assignment_type) == 'project' ? 'selected' : '' }}>
                                        🚀 Project
                                    </option>
                                    <option value="essay" {{ old('assignment_type', $assignment->assignment_type) == 'essay' ? 'selected' : '' }}>
                                        📄 Essay
                                    </option>
                                    <option value="coding" {{ old('assignment_type', $assignment->assignment_type) == 'coding' ? 'selected' : '' }}>
                                        💻 Coding
                                    </option>
                                    <option value="presentation" {{ old('assignment_type', $assignment->assignment_type) == 'presentation' ? 'selected' : '' }}>
                                        🎤 Presentation
                                    </option>
                                </select>
                                @error('assignment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Difficulty Level -->
                            <div class="col-md-6 mb-4">
                                <label for="difficulty_level" class="form-label">
                                    <i class="fas fa-signal me-1"></i>Difficulty Level <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('difficulty_level') is-invalid @enderror" 
                                        id="difficulty_level" 
                                        name="difficulty_level" 
                                        required>
                                    <option value="">Select Difficulty</option>
                                    <option value="beginner" {{ old('difficulty_level', $assignment->difficulty_level) == 'beginner' ? 'selected' : '' }}>
                                        🟢 Beginner
                                    </option>
                                    <option value="intermediate" {{ old('difficulty_level', $assignment->difficulty_level) == 'intermediate' ? 'selected' : '' }}>
                                        🟡 Intermediate
                                    </option>
                                    <option value="advanced" {{ old('difficulty_level', $assignment->difficulty_level) == 'advanced' ? 'selected' : '' }}>
                                        🔴 Advanced
                                    </option>
                                </select>
                                @error('difficulty_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Due Date -->
                            <div class="col-md-4 mb-4">
                                <label for="due_date" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>Due Date
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" 
                                       name="due_date" 
                                       value="{{ old('due_date', $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '') }}">
                                <small class="form-text text-muted">Optional - Leave blank for no deadline</small>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Points -->
                            <div class="col-md-4 mb-4">
                                <label for="points" class="form-label">
                                    <i class="fas fa-star me-1"></i>Points
                                </label>
                                <input type="number" 
                                       class="form-control @error('points') is-invalid @enderror" 
                                       id="points" 
                                       name="points" 
                                       value="{{ old('points', $assignment->points) }}" 
                                       min="0" 
                                       max="1000">
                                @error('points')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estimated Time -->
                            <div class="col-md-4 mb-4">
                                <label for="estimated_time" class="form-label">
                                    <i class="fas fa-clock me-1"></i>Estimated Time (minutes)
                                </label>
                                <input type="number" 
                                       class="form-control @error('estimated_time') is-invalid @enderror" 
                                       id="estimated_time" 
                                       name="estimated_time" 
                                       value="{{ old('estimated_time', $assignment->estimated_time) }}" 
                                       min="1">
                                @error('estimated_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('teacher.assignments.show', [$course, $lesson, $assignment]) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Assignment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
