@extends('layouts.admin')

@section('title', 'Email Testing')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Email Testing</h1>
            <p class="text-muted">Test and preview payment receipt emails</p>
        </div>
    </div>

    <div class="row">
        <!-- Test Email Form -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Send Test Email</h6>
                </div>
                <div class="card-body">
                    <form id="testEmailForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="user_id">Select Student:</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">Choose a student...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="course_id">Select Course:</label>
                            <select class="form-control" id="course_id" name="course_id" required>
                                <option value="">Choose a course...</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">
                                        {{ $course->title }} - RM{{ number_format($course->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="test_email">Send Test Email To:</label>
                            <input type="email" class="form-control" id="test_email" name="test_email" 
                                   placeholder="Enter email address..." required>
                            <small class="form-text text-muted">
                                This is where the test email will be sent (can be different from student's email)
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="sendTestBtn">
                                <i class="fas fa-envelope mr-1"></i> Send Test Email
                            </button>
                            
                            <button type="button" class="btn btn-info" id="previewBtn">
                                <i class="fas fa-eye mr-1"></i> Preview Email
                            </button>
                        </div>
                    </form>

                    <!-- Status Messages -->
                    <div id="statusMessage" class="mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>

        <!-- Email Preview -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Email Preview</h6>
                </div>
                <div class="card-body">
                    <div id="emailPreview" class="border rounded p-3" style="height: 500px; overflow-y: auto; background: #f8f9fa;">
                        <p class="text-muted text-center">Select a student and course, then click "Preview Email" to see how the email will look.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Testing Info -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">📧 Email Testing Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="font-weight-bold">Current Mail Settings:</h6>
                            <ul class="list-unstyled">
                                <li><strong>Driver:</strong> {{ config('mail.mailer') }}</li>
                                <li><strong>From:</strong> {{ config('mail.from.address') }}</li>
                                <li><strong>Name:</strong> {{ config('mail.from.name') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6 class="font-weight-bold">Test Features:</h6>
                            <ul class="list-unstyled">
                                <li>✅ Preview email design</li>
                                <li>✅ Send test emails</li>
                                <li>✅ Test with real data</li>
                                <li>✅ Mobile responsive preview</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6 class="font-weight-bold">Tips:</h6>
                            <ul class="list-unstyled small">
                                <li>• Use your own email for testing</li>
                                <li>• Check spam folder if not received</li>
                                <li>• Preview shows exact email content</li>
                                <li>• Test with different courses/students</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .gap-2 {
        gap: 0.5rem;
    }
    
    #emailPreview iframe {
        width: 100%;
        border: none;
    }
    
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('testEmailForm');
    const sendBtn = document.getElementById('sendTestBtn');
    const previewBtn = document.getElementById('previewBtn');
    const statusMessage = document.getElementById('statusMessage');
    const emailPreview = document.getElementById('emailPreview');

    // Send Test Email
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        // Show loading state
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Sending...';
        sendBtn.disabled = true;
        
        fetch('{{ route("admin.emails.test.send") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('success', data.message);
            } else {
                showMessage('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('danger', 'An error occurred while sending the test email.');
        })
        .finally(() => {
            sendBtn.innerHTML = '<i class="fas fa-envelope mr-1"></i> Send Test Email';
            sendBtn.disabled = false;
        });
    });

    // Preview Email
    previewBtn.addEventListener('click', function() {
        const userId = document.getElementById('user_id').value;
        const courseId = document.getElementById('course_id').value;
        
        if (!userId || !courseId) {
            showMessage('warning', 'Please select both a student and a course first.');
            return;
        }
        
        // Show loading state
        previewBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...';
        previewBtn.disabled = true;
        emailPreview.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading preview...</div>';
        
        const previewUrl = `{{ route("admin.emails.test.preview") }}?user_id=${userId}&course_id=${courseId}`;
        
        fetch(previewUrl)
        .then(response => response.text())
        .then(html => {
            emailPreview.innerHTML = `<iframe srcdoc="${html.replace(/"/g, '&quot;')}" style="width: 100%; height: 450px; border: none;"></iframe>`;
        })
        .catch(error => {
            console.error('Error:', error);
            emailPreview.innerHTML = '<p class="text-danger">Error loading preview. Please try again.</p>';
        })
        .finally(() => {
            previewBtn.innerHTML = '<i class="fas fa-eye mr-1"></i> Preview Email';
            previewBtn.disabled = false;
        });
    });

    function showMessage(type, message) {
        statusMessage.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        statusMessage.style.display = 'block';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            const alert = statusMessage.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => {
                    statusMessage.style.display = 'none';
                }, 150);
            }
        }, 5000);
    }
});
</script>
@endsection