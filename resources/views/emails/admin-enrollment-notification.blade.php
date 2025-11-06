<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Enrollment Notification</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dbeafe;
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
            color: #3b82f6;
            font-weight: 600;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            background-color: #f7fafc;
            padding: 20px 30px;
            text-align: center;
            color: #718096;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
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
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="icon">📊</div>
            <h1>New Student Enrollment</h1>
        </div>

        <div class="content">
            <div class="greeting">
                Hello Admin! 👋
            </div>

            <div class="message">
                A new student has successfully enrolled in a course on your platform. Here are the details:
            </div>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">📚 Course:</span>
                    <span class="info-value highlight">{{ $courseTitle }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">👨‍🏫 Instructor:</span>
                    <span class="info-value">{{ $instructorName }}</span>
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
                    <span class="info-label">📅 Date:</span>
                    <span class="info-value">{{ $enrollmentDate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">💰 Amount:</span>
                    <span class="info-value">{{ $amountPaid }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">✅ Status:</span>
                    <span class="info-value">
                        <span class="badge">{{ $paymentStatus }}</span>
                    </span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $enrollmentUrl }}" class="button">
                    View Enrollment Details
                </a>
            </div>
        </div>

        <div class="footer">
            <p><strong>LearnHub</strong> - Admin Dashboard</p>
            <p style="margin-top: 15px; font-size: 12px; color: #a0aec0;">
                This is an automated notification.
            </p>
        </div>
    </div>
</body>
</html>
