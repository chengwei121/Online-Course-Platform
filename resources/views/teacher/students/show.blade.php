@extends('teacher.layouts.app')

@section('title', 'Student Details - ' . $student->name)

@push('styles')
<style>
    .student-profile-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
    }

    .student-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 1rem;
    }

    .student-avatar-large-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 2rem;
        border: 4px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 1rem;
    }

    .profile-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        padding: 1rem;
        background: #f7fafc;
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }

    .info-label {
        font-size: 0.875rem;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1.1rem;
        color: #2d3748;
        font-weight: 500;
    }

    .enrollments-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-title {
        color: #2d3748;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .course-card {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .course-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }

    .course-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .course-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }

    .course-status {
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-active {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-completed {
        background: #bee3f8;
        color: #2a4365;
    }

    .status-pending {
        background: #fefcbf;
        color: #744210;
    }

    .course-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #718096;
        font-size: 0.875rem;
    }

    .progress-section {
        margin-top: 1rem;
    }

    .progress-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .progress-label {
        font-weight: 600;
        color: #4a5568;
        font-size: 0.875rem;
    }

    .progress-percentage {
        font-weight: 700;
        color: #667eea;
        font-size: 0.875rem;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .progress-details {
        display: flex;
        justify-content: space-between;
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #718096;
    }

    .action-buttons {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-message {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }

    .btn-message:hover {
        background: linear-gradient(135deg, #38a169, #2f855a);
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
    }

    .btn-back {
        background: #f7fafc;
        color: #4a5568;
        border: 2px solid #e2e8f0;
    }

    .btn-back:hover {
        background: #edf2f7;
        color: #2d3748;
        text-decoration: none;
        border-color: #cbd5e0;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #718096;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #cbd5e0;
    }

    @media (max-width: 768px) {
        .student-profile-container {
            padding: 1rem 0;
        }

        .profile-header {
            padding: 1.5rem 0;
            margin-bottom: 1rem;
        }

        .profile-card,
        .enrollments-section {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .course-header {
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .course-meta {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="student-profile-container">
    <!-- Header -->
    <div class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        @if($student->avatar && file_exists(public_path($student->avatar)))
                            <img src="{{ asset($student->avatar) }}" 
                                 alt="{{ $student->name }}" 
                                 class="student-avatar-large me-4"
                                 onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.svg') }}'">
                        @else
                            <div class="student-avatar-large-placeholder me-4">
                                {{ strtoupper(substr($student->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <h1 class="mb-2">{{ $student->name }}</h1>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-envelope me-2"></i>{{ $student->email }}
                            </p>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Joined {{ $student->created_at->format('F d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('teacher.students.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Students
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Student Information -->
        <div class="profile-card">
            <h3 class="section-title">
                <i class="fas fa-user-circle"></i>
                Student Information
            </h3>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ $student->name }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $student->email }}</div>
                </div>
                
                @if($student->phone)
                <div class="info-item">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">{{ $student->phone }}</div>
                </div>
                @endif
                
                @if($student->date_of_birth)
                <div class="info-item">
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('F d, Y') }}</div>
                </div>
                @endif
                
                @if($student->address)
                <div class="info-item">
                    <div class="info-label">Address</div>
                    <div class="info-value">{{ $student->address }}</div>
                </div>
                @endif
                
                <div class="info-item">
                    <div class="info-label">Member Since</div>
                    <div class="info-value">{{ $student->created_at->format('F d, Y') }}</div>
                </div>
            </div>
            
            @if($student->bio)
            <div class="mt-4">
                <h5 class="mb-3">About</h5>
                <p class="text-muted">{{ $student->bio }}</p>
            </div>
            @endif
        </div>

        <!-- Course Enrollments -->
        <div class="enrollments-section">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-graduation-cap"></i>
                    Course Enrollments ({{ $enrollments->count() }})
                </h3>
            </div>

            @if($enrollments->count() > 0)
                @foreach($enrollments as $enrollment)
                    <div class="course-card">
                        <div class="course-header">
                            <h4 class="course-title">{{ $enrollment->course->title }}</h4>
                            <span class="course-status status-{{ $enrollment->status }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </div>

                        <div class="course-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Enrolled: {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            
                            @if($enrollment->completed_at)
                            <div class="meta-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Completed: {{ $enrollment->completed_at->format('M d, Y') }}</span>
                            </div>
                            @endif
                            
                            <div class="meta-item">
                                <i class="fas fa-credit-card"></i>
                                <span>Payment: {{ ucfirst($enrollment->payment_status) }}</span>
                            </div>
                            
                            @if($enrollment->amount_paid)
                            <div class="meta-item">
                                <i class="fas fa-coins"></i>
                                <span>Amount: RM{{ number_format($enrollment->amount_paid, 2) }}</span>
                            </div>
                            @endif
                        </div>

                        @if(isset($progress[$enrollment->course_id]))
                            @php $courseProgress = $progress[$enrollment->course_id]; @endphp
                            <div class="progress-section">
                                <div class="progress-header">
                                    <span class="progress-label">Learning Progress</span>
                                    <span class="progress-percentage">{{ $courseProgress['percentage'] }}%</span>
                                </div>
                                
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $courseProgress['percentage'] }}%"></div>
                                </div>
                                
                                <div class="progress-details">
                                    <span>{{ $courseProgress['completed_lessons'] }} of {{ $courseProgress['total_lessons'] }} lessons completed</span>
                                    @if($courseProgress['percentage'] == 100)
                                        <span class="text-success">
                                            <i class="fas fa-trophy me-1"></i>Course Completed!
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($enrollment->course->description)
                        <div class="mt-3">
                            <p class="text-muted mb-0">{{ Str::limit($enrollment->course->description, 150) }}</p>
                        </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h4>No Course Enrollments</h4>
                    <p>This student is not currently enrolled in any of your courses.</p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn-action btn-message" onclick="openMessageModal({{ $student->id }}, '{{ $student->name }}')">
                <i class="fas fa-envelope me-2"></i>
                Send Message
            </button>
            
            <a href="{{ route('teacher.students.index') }}" class="btn-action btn-back">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Students List
            </a>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-envelope me-2"></i>
                    Send Message to <span id="studentName">{{ $student->name }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="messageForm">
                    <input type="hidden" id="studentId" name="student_id" value="{{ $student->id }}">
                    
                    <div class="mb-3">
                        <label for="messageSubject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="messageSubject" name="subject" placeholder="Enter message subject">
                    </div>
                    
                    <div class="mb-3">
                        <label for="messageText" class="form-label">Message</label>
                        <textarea class="form-control" id="messageText" name="message" rows="6" required placeholder="Write your message here..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This message will be sent to {{ $student->email }}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="sendMessage()">
                    <i class="fas fa-paper-plane me-2"></i>Send Message
                </button>
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
    document.getElementById('messageSubject').value = '';
    document.getElementById('messageText').value = '';
    
    var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
    messageModal.show();
}

function sendMessage() {
    const studentId = document.getElementById('studentId').value;
    const subject = document.getElementById('messageSubject').value;
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

// Animate progress bars on page load
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
});
</script>
@endpush