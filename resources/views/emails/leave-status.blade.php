<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #000000;
            color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status.approved {
            background: #10b981;
            color: white;
        }
        .status.rejected {
            background: #ef4444;
            color: white;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Leave Request {{ ucfirst($leave->status) }}</h1>
    </div>
    
    <div class="content">
        <h2>Hello {{ $leave->user->name }},</h2>
        
        <p>Your leave request has been <span class="status {{ $leave->status }}">{{ $leave->status }}</span></p>
        
        <p><strong>Leave Details:</strong></p>
        <ul>
            <li><strong>Type:</strong> {{ $leave->leaveType->name }}</li>
            <li><strong>From:</strong> {{ $leave->start_date }}</li>
            <li><strong>To:</strong> {{ $leave->end_date }}</li>
            <li><strong>Duration:</strong> {{ $leave->days }} working days</li>
            <li><strong>Reason:</strong> {{ $leave->reason }}</li>
        </ul>
        
        @if($leave->status === 'approved')
            <p>Your leave has been approved. Please ensure all your pending tasks are completed before your leave starts.</p>
        @else
            <p>Unfortunately, your leave request has been rejected. Please contact your manager for more information.</p>
        @endif
        
        <a href="{{ url('/leaves') }}" class="button">View Leave Details</a>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} AIHRM. All rights reserved.</p>
    </div>
</body>
</html>
