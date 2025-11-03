<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Student Enrollment</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background-color: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
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
            color: #667eea;
            font-weight: 600;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background-color: #48bb78;
            color: white;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
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
            <div class="icon">🎓</div>
            <h1>New Student Enrollment!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello Teacher! 👋
            </div>

            <div class="message">
                Great news! A new student has enrolled in your course. Here are the enrollment details:
            </div>

            <!-- Enrollment Details -->
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">📚 Course:</span>
                    <span class="info-value highlight">{{ $courseTitle }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">👤 Student Name:</span>
                    <span class="info-value">{{ $studentName }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">📧 Student Email:</span>
                    <span class="info-value">{{ $studentEmail }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">📅 Enrollment Date:</span>
                    <span class="info-value">{{ $enrollmentDate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">💰 Amount Paid:</span>
                    <span class="info-value">{{ $amountPaid }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">✅ Payment Status:</span>
                    <span class="info-value">
                        <span class="badge">{{ $paymentStatus }}</span>
                    </span>
                </div>
            </div>

            <div class="message">
                You can now view this student in your course dashboard and track their progress as they learn from your content.
            </div>

            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="{{ $courseUrl }}" class="button">
                    View Course Dashboard
                </a>
            </div>

            <div class="message" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <strong>What's Next?</strong><br>
                • Welcome your new student<br>
                • Monitor their progress through lessons<br>
                • Provide feedback on assignments<br>
                • Answer any questions they may have
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>LearnHub</strong> - Online Learning Platform</p>
            <p>Thank you for teaching with us! 🌟</p>
            <p style="margin-top: 15px; font-size: 12px; color: #a0aec0;">
                This is an automated notification. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
