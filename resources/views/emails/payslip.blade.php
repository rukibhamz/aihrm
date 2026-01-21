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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .payslip-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .payslip-table th,
        .payslip-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .payslip-table th {
            background: #667eea;
            color: white;
        }
        .total-row {
            font-weight: bold;
            background: #f0f0f0;
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
        <h1>Your Payslip is Ready</h1>
        <p>{{ date('F Y', mktime(0, 0, 0, $payslip->payroll->month, 1, $payslip->payroll->year)) }}</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $payslip->user->name }},</h2>
        
        <p>Your payslip for {{ date('F Y', mktime(0, 0, 0, $payslip->payroll->month, 1, $payslip->payroll->year)) }} is now available.</p>
        
        <table class="payslip-table">
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount</th>
            </tr>
            <tr>
                <td>Basic Salary</td>
                <td style="text-align: right;">${{ number_format($payslip->basic_salary, 2) }}</td>
            </tr>
            <tr>
                <td>Allowances</td>
                <td style="text-align: right;">${{ number_format($payslip->total_allowances, 2) }}</td>
            </tr>
            @if($payslip->overtime_amount > 0)
            <tr>
                <td>Overtime ({{ $payslip->overtime_hours }} hrs)</td>
                <td style="text-align: right;">${{ number_format($payslip->overtime_amount, 2) }}</td>
            </tr>
            @endif
            @if($payslip->bonus_amount > 0)
            <tr>
                <td>Bonuses</td>
                <td style="text-align: right;">${{ number_format($payslip->bonus_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td>Deductions</td>
                <td style="text-align: right;">-${{ number_format($payslip->total_deductions, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Net Salary</td>
                <td style="text-align: right;">${{ number_format($payslip->net_salary, 2) }}</td>
            </tr>
        </table>
        
        <p><strong>Attendance:</strong> {{ $payslip->worked_days }}/{{ $payslip->expected_days }} working days</p>
        
        <a href="{{ url('/my-payslips') }}" class="button">View Full Payslip</a>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} AIHRM. All rights reserved.</p>
        <p>This is an automated email. Please do not reply.</p>
    </div>
</body>
</html>
