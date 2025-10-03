<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $receiptNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .receipt-info {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e9ecef;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .receipt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 8px 0;
        }
        
        .receipt-row:last-child {
            margin-bottom: 0;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            font-weight: 600;
            color: #28a745;
        }
        
        .label {
            font-weight: 500;
            color: #6c757d;
        }
        
        .value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .course-details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e9ecef;
        }
        
        .course-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .course-description {
            color: #6c757d;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .course-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .meta-item {
            background: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }
        
        .cta-button {
            background: #2c3e50;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            font-weight: 500;
            margin: 20px 0;
            font-size: 14px;
        }
        
        .cta-button:hover {
            background: #34495e;
            text-decoration: none;
            color: white;
        }
        
        .help-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #e9ecef;
        }
        
        .help-section p {
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .footer {
            background: #2c3e50;
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .contact-info {
            font-size: 13px;
            opacity: 0.8;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .disclaimer {
            font-size: 11px;
            opacity: 0.7;
            line-height: 1.4;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .content {
                padding: 20px;
            }
            
            .header {
                padding: 20px;
            }
            
            .receipt-info, .course-details, .help-section {
                padding: 15px;
            }
            
            .course-meta {
                flex-direction: column;
            }
            
            .receipt-row {
                flex-direction: column;
                gap: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- TEST EMAIL NOTICE -->
        @if(isset($isTest) && $isTest)
        <div style="background: #ff6b6b; color: white; padding: 15px; text-align: center; font-weight: bold; font-size: 16px; border-bottom: 3px solid #e74c3c;">
            🧪 TEST EMAIL - This is a test email sent from the admin panel to verify email functionality
        </div>
        @endif
        
        <!-- Header -->
        <div class="header">
            <h1>Payment Successful</h1>
            <p>Receipt #{{ $receiptNumber }}</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hi {{ $student->name }},
            </div>
            
            <p>Your payment has been successfully processed. You now have access to your course.</p>
            
            <!-- Receipt Information -->
            <div class="receipt-info">
                <div class="section-title">Payment Details</div>
                
                <div class="receipt-row">
                    <span class="label">Receipt Number: </span>
                    <span class="value">{{ $receiptNumber }}</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Payment Date: </span>
                    <span class="value">{{ $paymentDate->format('M d, Y') }}</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Payment Method: </span>
                    <span class="value">PayPal</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Student Name: </span>
                    <span class="value">{{ $student->name }}</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Email: </span>
                    <span class="value">{{ $student->email }}</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Total Amount Paid: </span>
                    <span class="value">RM{{ number_format($amount, 2) }}</span>
                </div>
            </div>
            
            <!-- Course Details -->
            <div class="course-details">
                <div class="section-title">Course Information</div>
                
                <div class="course-title">{{ $course->title }}</div>
                
                @if($course->description)
                    <div class="course-description">{{ Str::limit($course->description, 120) }}</div>
                @endif
                
                <div class="course-meta">
                    @if($course->category)
                        <span class="meta-item">{{ $course->category->name }}</span>
                    @endif
                    
                    @if($course->learning_hours)
                        <span class="meta-item">{{ $course->learning_hours }} hours</span>
                    @endif
                    
                    @if($course->level)
                        <span class="meta-item">{{ ucfirst($course->level) }} Level</span>
                    @endif
                    
                    @if($course->instructor)
                        <span class="meta-item">Instructor: {{ $course->instructor->name }}</span>
                    @endif
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('client.courses.show', $course->slug) }}" class="cta-button">
                    Start Learning
                </a>
            </div>
            
            <div class="help-section">
                <p><strong>Need Help?</strong></p>
                <p>If you have questions about your course, please contact our support team.</p>
                <p>
                    <a href="mailto:{{ $companyInfo['email'] }}" style="color: #2c3e50;">
                        {{ $companyInfo['email'] }}
                    </a>
                </p>
            </div>
        </div>
        
        <!-- TEST EMAIL CONFIRMATION -->
        @if(isset($isTest) && $isTest)
        <div style="background: #f8f9fa; padding: 20px; margin: 0; border-top: 3px solid #ff6b6b; text-align: center;">
            <h3 style="color: #ff6b6b; margin: 0 0 10px 0;">✅ TEST EMAIL CONFIRMATION</h3>
            <p style="margin: 5px 0; color: #666;">If you received this email, your email system is working correctly!</p>
            <p style="margin: 5px 0; color: #666; font-size: 14px;">
                <strong>Sent at:</strong> {{ now()->format('Y-m-d H:i:s') }} (Server Time)<br>
                <strong>Test performed by:</strong> Admin Panel Email Testing System
            </p>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <div class="company-name">{{ $companyInfo['name'] }}</div>
            <div class="contact-info">
                {{ $companyInfo['address'] }}<br>
                {{ $companyInfo['phone'] }}<br>
                {{ $companyInfo['website'] }}
            </div>
            
            <div class="disclaimer">
                <p>This is an automated receipt. Please keep this email for your records.</p>
                <p>&copy; {{ date('Y') }} {{ $companyInfo['name'] }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
