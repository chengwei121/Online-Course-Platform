@extends('teacher.layouts.app')

@section('title', 'Create Assignment - ' . $lesson->title)
@section('page-title', 'Create Assignment')

@section('content')
<div class="container-fluid">
    <!-- Course Context -->
    <div class="card mb-4">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}">{{ Str::limit($course->title, 20) }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.lessons.index', $course) }}">Lessons</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}">{{ Str::limit($lesson->title, 20) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Assignment</li>
                </ol>
            </nav>
            <h1 class="h3 mb-1">Create Assignment</h1>
            <p class="text-muted mb-0">Add an assignment to <strong>{{ $lesson->title }}</strong></p>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <form action="{{ route('teacher.assignments.store', [$course, $lesson]) }}" method="POST" id="assignmentForm">
                @csrf
                
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Assignment Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Assignment Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Enter a clear assignment title" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Assignment Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Brief overview of what students need to accomplish" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="assignment_type" class="form-label">Assignment Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('assignment_type') is-invalid @enderror" 
                                            id="assignment_type" name="assignment_type" required>
                                        <option value="">Select type...</option>
                                        <option value="project" {{ old('assignment_type') == 'project' ? 'selected' : '' }}>Project</option>
                                        <option value="quiz" {{ old('assignment_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                        <option value="essay" {{ old('assignment_type') == 'essay' ? 'selected' : '' }}>Essay</option>
                                        <option value="coding" {{ old('assignment_type') == 'coding' ? 'selected' : '' }}>Coding Exercise</option>
                                        <option value="presentation" {{ old('assignment_type') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                                    </select>
                                    @error('assignment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="difficulty_level" class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                                    <select class="form-select @error('difficulty_level') is-invalid @enderror" 
                                            id="difficulty_level" name="difficulty_level" required>
                                        <option value="">Select level...</option>
                                        <option value="beginner" {{ old('difficulty_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ old('difficulty_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                        <option value="advanced" {{ old('difficulty_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    </select>
                                    @error('difficulty_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="points" class="form-label">Points</label>
                                    <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                           id="points" name="points" value="{{ old('points', 100) }}" 
                                           min="0" max="1000" placeholder="100">
                                    @error('points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="estimated_time" class="form-label">Estimated Time (minutes)</label>
                                    <input type="number" class="form-control @error('estimated_time') is-invalid @enderror" 
                                           id="estimated_time" name="estimated_time" value="{{ old('estimated_time') }}" 
                                           min="1" placeholder="60">
                                    @error('estimated_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="datetime-local" class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" name="due_date" value="{{ old('due_date') }}">
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list-ol me-2"></i>Instructions & Requirements
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="instructions" class="form-label">Detailed Instructions <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                      id="instructions" name="instructions" rows="8" 
                                      placeholder="Provide clear, step-by-step instructions..." required>{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Include requirements, submission format, evaluation criteria, and any resources needed.
                            </div>
                        </div>

                        <!-- Dynamic content based on assignment type -->
                        <div id="type-specific-content">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Assignment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Assignment Type Guide -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Assignment Types
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-project-diagram me-1"></i>Project
                        </h6>
                        <p class="small text-muted mb-0">Practical application of skills. Students build something tangible.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-question-circle me-1"></i>Quiz
                        </h6>
                        <p class="small text-muted mb-0">Knowledge assessment with multiple choice, true/false, or short answers.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-file-alt me-1"></i>Essay
                        </h6>
                        <p class="small text-muted mb-0">Written assignment requiring analysis, reflection, or research.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-code me-1"></i>Coding Exercise
                        </h6>
                        <p class="small text-muted mb-0">Programming challenges or debugging exercises.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-presentation me-1"></i>Presentation
                        </h6>
                        <p class="small text-muted mb-0">Oral presentation of research, project, or concept explanation.</p>
                    </div>
                </div>
            </div>

            <!-- Best Practices -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Best Practices
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Clear Objectives:</strong> Specify exactly what students should accomplish
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Step-by-Step:</strong> Break complex tasks into manageable steps
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Examples:</strong> Provide samples or references when helpful
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Criteria:</strong> Explain how work will be evaluated
                        </div>
                    </div>
                    <div class="d-flex">
                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Resources:</strong> Link to materials, tools, or references needed
                        </div>
                    </div>
                </div>
            </div>

            <!-- Difficulty Guide -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-signal me-2"></i>Difficulty Levels
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-success">Beginner</h6>
                        <p class="small text-muted mb-0">Basic concepts, following tutorials, simple practice exercises</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-warning">Intermediate</h6>
                        <p class="small text-muted mb-0">Apply knowledge to new situations, moderate problem-solving</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-danger">Advanced</h6>
                        <p class="small text-muted mb-0">Complex projects, research, innovation, teaching others</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const assignmentTypeSelect = document.getElementById('assignment_type');
    const typeSpecificContent = document.getElementById('type-specific-content');
    const instructionsTextarea = document.getElementById('instructions');

    // Handle assignment type change
    assignmentTypeSelect.addEventListener('change', function() {
        updateTypeSpecificContent(this.value);
        updateInstructionsPlaceholder(this.value);
    });

    function updateTypeSpecificContent(type) {
        let content = '';
        
        switch(type) {
            case 'quiz':
                content = `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>Quiz Guidelines</h6>
                        <ul class="mb-0 small">
                            <li>Specify number of questions and time limit</li>
                            <li>Indicate if it's open book or closed book</li>
                            <li>List topics that will be covered</li>
                            <li>Mention the format (multiple choice, short answer, etc.)</li>
                        </ul>
                    </div>
                `;
                break;
            case 'project':
                content = `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>Project Guidelines</h6>
                        <ul class="mb-0 small">
                            <li>Define project scope and deliverables</li>
                            <li>Specify required features or components</li>
                            <li>Include technical requirements</li>
                            <li>Mention submission format (files, GitHub repo, etc.)</li>
                        </ul>
                    </div>
                `;
                break;
            case 'essay':
                content = `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>Essay Guidelines</h6>
                        <ul class="mb-0 small">
                            <li>Specify word count or page length</li>
                            <li>Define required structure (intro, body, conclusion)</li>
                            <li>List required sources or research</li>
                            <li>Include citation format (APA, MLA, etc.)</li>
                        </ul>
                    </div>
                `;
                break;
            case 'coding':
                content = `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>Coding Exercise Guidelines</h6>
                        <ul class="mb-0 small">
                            <li>Specify programming language and version</li>
                            <li>Include input/output examples</li>
                            <li>Mention testing requirements</li>
                            <li>List any libraries or frameworks to use</li>
                        </ul>
                    </div>
                `;
                break;
            case 'presentation':
                content = `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>Presentation Guidelines</h6>
                        <ul class="mb-0 small">
                            <li>Specify presentation length and format</li>
                            <li>List required sections or topics</li>
                            <li>Mention visual aid requirements</li>
                            <li>Include audience and delivery expectations</li>
                        </ul>
                    </div>
                `;
                break;
        }
        
        typeSpecificContent.innerHTML = content;
    }

    function updateInstructionsPlaceholder(type) {
        const placeholders = {
            'quiz': 'This quiz will test your understanding of the lesson concepts. You will have 30 minutes to complete 10 questions covering...',
            'project': 'Create a [project type] that demonstrates your understanding of [concept]. Your project should include...',
            'essay': 'Write a [word count] essay analyzing [topic]. Your essay should include an introduction, main arguments, and conclusion...',
            'coding': 'Complete the following coding exercise. Write a function that [requirement]. Your solution should...',
            'presentation': 'Prepare a [duration]-minute presentation on [topic]. Your presentation should cover...'
        };
        
        if (placeholders[type]) {
            instructionsTextarea.placeholder = placeholders[type];
        }
    }

    // Auto-suggest assignment titles based on lesson
    const titleInput = document.getElementById('title');
    titleInput.addEventListener('focus', function() {
        if (!this.value) {
            const lessonTitle = '{{ $lesson->title }}';
            const suggestions = [
                `${lessonTitle} - Practice Exercise`,
                `${lessonTitle} - Project Assignment`,
                `${lessonTitle} - Knowledge Check`,
                `${lessonTitle} - Hands-on Activity`
            ];
            
            // You could implement a dropdown with suggestions here
        }
    });

    // Set minimum due date to tomorrow
    const dueDateInput = document.getElementById('due_date');
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    dueDateInput.min = tomorrow.toISOString().slice(0, 16);
});
</script>
@endpush