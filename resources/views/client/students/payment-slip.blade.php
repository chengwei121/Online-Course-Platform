<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $enrollment->course->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .receipt-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .receipt-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .receipt-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .receipt-body {
            padding: 40px;
        }
        .receipt-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }
        .info-section h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .info-section p {
            font-size: 14px;
            margin: 5px 0;
        }
        .receipt-details {
            margin: 30px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        .detail-value {
            text-align: right;
        }
        .total-section {
            background: #f8f9fa;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }
        .footer-note {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            color: #666;
            font-size: 12px;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .print-button:hover {
            background: #5568d3;
        }
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #6c757d;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .back-button:hover {
            background: #5a6268;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
            }
            .print-button, .back-button {
                display: none;
            }
        }
        @media (max-width: 768px) {
            .receipt-info {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('client.payments.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Payments
    </a>
    
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print Receipt
    </button>

    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <h1>PAYMENT RECEIPT</h1>
            <p>LearnHub Online Learning Platform</p>
            <p style="margin-top: 10px;">Receipt #{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <!-- Info Section -->
            <div class="receipt-info">
                <div class="info-section">
                    <h3>Student Information</h3>
                    <p><strong>Name:</strong> {{ $enrollment->user->name }}</p>
                    <p><strong>Email:</strong> {{ $enrollment->user->email }}</p>
                    <p><strong>Student ID:</strong> #{{ $enrollment->user->id }}</p>
                </div>
                <div class="info-section">
                    <h3>Payment Details</h3>
                    <p><strong>Date:</strong> {{ $enrollment->created_at->format('F d, Y') }}</p>
                    <p><strong>Time:</strong> {{ $enrollment->created_at->format('h:i A') }}</p>
                    <p><strong>Status:</strong> <span class="status-badge status-completed">{{ ucfirst($enrollment->payment_status) }}</span></p>
                </div>
            </div>

            <!-- Course Details -->
            <h3 style="margin-bottom: 15px; color: #667eea;">Course Details</h3>
            <div class="receipt-details">
                <div class="detail-row">
                    <span class="detail-label">Course Name</span>
                    <span class="detail-value">{{ $enrollment->course->title }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Category</span>
                    <span class="detail-value">{{ $enrollment->course->category->name ?? 'General' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Instructor</span>
                    <span class="detail-value">{{ $enrollment->course->instructor->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Enrollment Date</span>
                    <span class="detail-value">{{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('F d, Y') : $enrollment->created_at->format('F d, Y') }}</span>
                </div>
            </div>

            <!-- Payment Breakdown -->
            <div class="total-section">
                <div class="detail-row" style="border: none; margin-bottom: 10px;">
                    <span class="detail-label">Course Price</span>
                    <span class="detail-value">RM{{ number_format($enrollment->amount_paid ?? $enrollment->course->price, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Total Amount Paid</span>
                    <span>RM{{ number_format($enrollment->amount_paid ?? $enrollment->course->price, 2) }}</span>
                </div>
            </div>

            <!-- Additional Info -->
            <div style="margin-top: 30px; padding: 20px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">
                <p style="margin: 0; font-size: 14px;">
                    <strong>📚 Course Access:</strong> You now have lifetime access to all course materials including lessons, assignments, and resources.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-note">
            <p><strong>Thank you for your purchase!</strong></p>
            <p style="margin-top: 10px;">
                This is an official receipt from LearnHub. For any inquiries, please contact us at elearningplatform118@gmail.com
            </p>
            <p style="margin-top: 10px; font-size: 11px;">
                Generated on {{ now()->format('F d, Y \a\t h:i A') }}
            </p>
        </div>
    </div>

    <script>
        // Optional: Auto-print when page loads
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
