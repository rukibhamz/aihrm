<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #111; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .company-info { font-size: 10px; color: #666; }
        
        .title { margin-bottom: 20px; font-size: 16px; font-weight: bold; text-transform: uppercase; }
        
        .grid { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .grid td { padding: 8px; vertical-align: top; }
        .label { font-weight: bold; color: #666; width: 120px; }
        
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th { background: #f9f9f9; padding: 10px; text-align: left; border-bottom: 1px solid #ddd; font-weight: bold; }
        .table td { padding: 10px; border-bottom: 1px solid #eee; }
        .amount { text-align: right; }
        
        .total-row td { border-top: 2px solid #000; font-weight: bold; font-size: 14px; padding-top: 15px; }
        .net-pay { background: #f0f0f0; padding: 15px; font-size: 16px; font-weight: bold; text-align: right; border-radius: 4px; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AIHRM Inc.</div>
        <div class="company-info">123 Business Park, Lagos, Nigeria • taxid: 99887766</div>
    </div>

    <div class="title">Payslip: {{ date('F Y', mktime(0, 0, 0, $payslip->payroll->month, 10)) }}</div>

    <table class="grid">
        <tr>
            <td width="50%">
                <table>
                    <tr><td class="label">Employee:</td><td>{{ $payslip->user->name }}</td></tr>
                    <tr><td class="label">Designation:</td><td>{{ $payslip->user->employee->designation->title ?? 'N/A' }}</td></tr>
                    <tr><td class="label">Department:</td><td>{{ $payslip->user->employee->department->name ?? 'N/A' }}</td></tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr><td class="label">Payslip ID:</td><td>#{{ str_pad($payslip->id, 6, '0', STR_PAD_LEFT) }}</td></tr>
                    <tr><td class="label">Pay Date:</td><td>{{ $payslip->payroll->payment_date ? $payslip->payroll->payment_date->format('d M Y') : 'Processing' }}</td></tr>
                    <tr><td class="label">Bank:</td><td>Access Bank (**** 1234)</td></tr>
                </table>
            </td>
        </tr>
    </table>

    @php
        $breakdown = json_decode($payslip->breakdown, true) ?? [];
        $earnings = $breakdown['earnings'] ?? [];
        $deductions = $breakdown['deductions'] ?? [];
    @endphp

    <table class="table">
        <thead>
            <tr>
                <th width="50%">Earnings</th>
                <th width="15%" class="amount">Amount (₦)</th>
                <th width="5%"></th>
                <th width="30%">Deductions</th>
                <th width="15%" class="amount">Amount (₦)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @foreach($earnings as $label => $amount)
                        <div style="padding: 4px 0;">{{ $label }}</div>
                    @endforeach
                </td>
                <td class="amount">
                    @foreach($earnings as $amount)
                        <div style="padding: 4px 0;">{{ number_format($amount, 2) }}</div>
                    @endforeach
                </td>
                <td></td>
                <td>
                    @foreach($deductions as $label => $amount)
                        <div style="padding: 4px 0;">{{ $label }}</div>
                    @endforeach
                </td>
                <td class="amount">
                    @foreach($deductions as $amount)
                        <div style="padding: 4px 0;">{{ number_format($amount, 2) }}</div>
                    @endforeach
                </td>
            </tr>
            <tr class="total-row">
                <td>Total Earnings</td>
                <td class="amount">{{ number_format($payslip->basic_salary + $payslip->total_allowances, 2) }}</td>
                <td></td>
                <td>Total Deductions</td>
                <td class="amount">{{ number_format($payslip->total_deductions, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="net-pay">
        Net Pay: ₦{{ number_format($payslip->net_salary, 2) }}
    </div>

    <div class="footer">
        Generated on {{ now()->format('d M Y H:i:s') }} • This is a computer-generated document and does not require a signature.
    </div>
</body>
</html>
