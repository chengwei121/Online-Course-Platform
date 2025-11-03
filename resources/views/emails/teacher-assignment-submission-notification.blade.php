<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Assignment Submission</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-box.late {
            background-color: #fee2e2;
            border-left-color: #ef4444;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #2d3748;
        }
        .info-value {
            color: #4a5568;
            text-align: right;
        }
        .highlight {
            color: #f59e0b;
            font-weight: 600;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .footer {
            background-color: #f7fafc;
            padding: 20px 30px;
            text-align: center;
            color: #718096;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: #10b981;
            color: white;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge.late {
            background-color: #ef4444;
        }
        .badge.on-time {
            background-color: #10b981;
        }
        .alert {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .alert.danger {
            background-color: #fee2e2;
            border-left-color: #ef4444;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 20px;
            }
            .info-row {
                flex-direction: column;
            }
            .info-value {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon">📝</div>
            <h1>New Assignment Submission!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello Teacher! 👋
            </div>

            <div class="message">
                A student has submitted an assignment that requires your review and grading.
            </div>

            @if($isLate)
                <div class="alert danger">
                    <strong>⚠️ Late Submission:</strong> This assignment was submitted after the due date.
                </div>
            @endif

            <!-- Submission Details -->
            <div class="info-box {{ $isLate ? 'late' : '' }}">
                <div class="info-row">
                    <span class="info-label">📚 Course:</span>
                    <span class="info-value highlight">{{ $courseTitle }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">📝 Assignment:</span>
                    <span class="info-value highlight">{{ $assignmentTitle }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">👤 Student:</span>
                    <span class="info-value">{{ $studentName }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">📧 Email:</span>
                    <span class="info-value">{{ $studentEmail }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">📅 Submitted:</span>
                    <span class="info-value">{{ $submissionDate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">⏰ Due Date:</span>
                    <span class="info-value">{{ $dueDate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">⏱️ Status:</span>
                    <span class="info-value">
                        <span class="badge {{ $isLate ? 'late' : 'on-time' }}">
                            {{ $isLate ? 'Late Submission' : 'On Time' }}
                        </span>
                    </span>
                </div>
                @if($hasFile)
                <div class="info-row">
                    <span class="info-label">📎 Attachment:</span>
                    <span class="info-value">
                        <span class="badge">File Attached</span>
                    </span>
                </div>
                @endif
            </div>

            <div class="message">
                Please review the submission and provide feedback to help your student learn and improve.
            </div>

            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="{{ $submissionUrl }}" class="button">
                    Review & Grade Submission
                </a>
            </div>

            <div class="message" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <strong>📊 Grading Tips:</strong><br>
                • Review the submission content carefully<br>
                • Check if the student met all requirements<br>
                • Provide constructive feedback<br>
                • Grade fairly and promptly<br>
                • Encourage the student's progress
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>LearnHub</strong> - Online Learning Platform</p>
            <p>Help your students succeed! 🌟</p>
            <p style="margin-top: 15px; font-size: 12px; color: #a0aec0;">
                This is an automated notification. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
