<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Confirmation</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        .success-banner {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
            text-align: center;
        }
        .success-banner h2 {
            margin: 0 0 10px 0;
            color: #065f46;
            font-size: 20px;
        }
        .success-banner p {
            margin: 0;
            color: #047857;
            font-weight: 500;
        }
        .info-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
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
            color: #10b981;
            font-weight: 600;
        }
        .button {
            display: inline-block;
            padding: 14px 35px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            font-size: 16px;
        }
        .instructions {
            background-color: #eff6ff;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .instructions h3 {
            margin: 0 0 15px 0;
            color: #1e40af;
            font-size: 16px;
        }
        .instructions ul {
            margin: 0;
            padding-left: 20px;
            color: #4a5568;
        }
        .instructions li {
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f7fafc;
            padding: 20px 30px;
            text-align: center;
            color: #718096;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="icon">🎉</div>
            <h1>Enrollment Confirmed!</h1>
        </div>

        <div class="content">
            <div class="greeting">
                Hello {{ $studentName }}! 👋
            </div>

            <div class="success-banner">
                <h2>✅ You're All Set!</h2>
                <p>Your enrollment has been successfully confirmed</p>
            </div>

            <div class="message">
                Congratulations! You have successfully enrolled in <span class="highlight">{{ $courseTitle }}</span>. 
                You now have full access to all course materials and can start your learning journey right away!
            </div>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">📚 Course:</span>
                    <span class="info-value">{{ $courseTitle }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">👨‍🏫 Instructor:</span>
                    <span class="info-value">{{ $instructorName }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">📅 Enrolled On:</span>
                    <span class="info-value">{{ $enrollmentDate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">💰 Amount Paid:</span>
                    <span class="info-value">{{ $amountPaid }}</span>
                </div>
            </div>

            <div class="instructions">
                <h3>🚀 What's Next?</h3>
                <ul>
                    <li>Access your course dashboard anytime from your account</li>
                    <li>Watch video lessons at your own pace</li>
                    <li>Complete assignments and track your progress</li>
                    <li>Interact with your instructor if you need help</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ $courseUrl }}" class="button">
                    🎓 Start Learning Now
                </a>
            </div>

            <div class="message" style="margin-top: 30px; font-size: 14px; color: #718096;">
                <strong>Note:</strong> You can always access this course from your student dashboard. Happy learning! 📖
            </div>
        </div>

        <div class="footer">
            <p><strong>LearnHub</strong> - Your Learning Journey Starts Here</p>
            <p style="margin-top: 15px; font-size: 12px; color: #a0aec0;">
                Need help? Contact us at support@learnhub.com
            </p>
        </div>
    </div>
</body>
</html>
