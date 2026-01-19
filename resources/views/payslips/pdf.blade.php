<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payslip->payroll->month }}/{{ $payslip->payroll->year }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #000; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .company-name { font-size: 20px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
        .payslip-title { font-size: 14px; margin-top: 5px; font-weight: bold; }
        
        .section { margin-bottom: 20px; }
        .section-title { font-size: 10px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #eee; margin-bottom: 10px; padding-bottom: 5px; }
        
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px 0; vertical-align: top; }
        .label { color: #666; width: 40%; }
        .value { text-align: right; font-weight: bold; }
        
        .grid { display: flex; flex-wrap: wrap; }
        .col { width: 48%; display: inline-block; vertical-align: top; }
        .col:last-child { margin-left: 4%; }
        
        .total-row { border-top: 1px solid #000; margin-top: 10px; padding-top: 10px; }
        .net-salary { font-size: 16px; font-weight: 900; }
        
        .footer { margin-top: 50px; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">AIHRM ENTERPRISE</div>
        <div class="payslip-title">PAYSLIP: {{ date('F', mktime(0, 0, 0, $payslip->payroll->month, 10)) }} {{ $payslip->payroll->year }}</div>
    </div>

    <div class="section">
        <div class="section-title">Employee Information</div>
        <div class="grid">
            <div class="col">
                <table>
                    <tr><td class="label">Name:</td><td class="value">{{ $payslip->user->name }}</td></tr>
                    <tr><td class="label">Staff ID:</td><td class="value">{{ $payslip->user->employee->staff_id ?? 'N/A' }}</td></tr>
                </table>
            </div>
            <div class="col">
                <table>
                    <tr><td class="label">Department:</td><td class="value">{{ $payslip->user->employee->department->name ?? 'N/A' }}</td></tr>
                    <tr><td class="label">Payment Date:</td><td class="value">{{ $payslip->payroll->payment_date ? $payslip->payroll->payment_date->format('d M Y') : 'Pending' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="grid">
            <!-- Earnings -->
            <div class="col">
                <div class="section-title">Earnings</div>
                <table>
                    <tr><td class="label">Basic Salary:</td><td class="value">₦{{ number_format($payslip->basic_salary, 2) }}</td></tr>
                    @isset($payslip->breakdown['housing'])
                    <tr><td class="label">Housing Allowance:</td><td class="value">₦{{ number_format($payslip->breakdown['housing'], 2) }}</td></tr>
                    @endisset
                    @isset($payslip->breakdown['transport'])
                    <tr><td class="label">Transport Allowance:</td><td class="value">₦{{ number_format($payslip->breakdown['transport'], 2) }}</td></tr>
                    @endisset
                    @if($payslip->overtime_amount > 0)
                    <tr><td class="label">Overtime:</td><td class="value">₦{{ number_format($payslip->overtime_amount, 2) }}</td></tr>
                    @endif
                    @if($payslip->bonus_amount > 0)
                    <tr><td class="label">Bonus:</td><td class="value">₦{{ number_format($payslip->bonus_amount, 2) }}</td></tr>
                    @endif
                </table>
            </div>
            
            <!-- Deductions -->
            <div class="col">
                <div class="section-title">Deductions</div>
                <table>
                    @isset($payslip->breakdown['tax'])
                    <tr><td class="label">PAYE Tax:</td><td class="value">₦{{ number_format($payslip->breakdown['tax'], 2) }}</td></tr>
                    @endisset
                    @isset($payslip->breakdown['pension'])
                    <tr><td class="label">Pension Contribution:</td><td class="value">₦{{ number_format($payslip->breakdown['pension'], 2) }}</td></tr>
                    @endisset
                    @if($payslip->loan_deduction > 0)
                    <tr><td class="label">Loan Repayment:</td><td class="value">₦{{ number_format($payslip->loan_deduction, 2) }}</td></tr>
                    @endif
                    @if($payslip->advance_deduction > 0)
                    <tr><td class="label">Salary Advance:</td><td class="value">₦{{ number_format($payslip->advance_deduction, 2) }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="total-row">
        <table>
            <tr><td class="label">Gross Salary:</td><td class="value">₦{{ number_format($payslip->basic_salary + $payslip->total_allowances + $payslip->overtime_amount + $payslip->bonus_amount, 2) }}</td></tr>
            <tr><td class="label">Total Deductions:</td><td class="value">₦{{ number_format($payslip->total_deductions, 2) }}</td></tr>
            <tr class="net-salary"><td class="label">NET SALARY:</td><td class="value">₦{{ number_format($payslip->net_salary, 2) }}</td></tr>
        </table>
    </div>

    <div class="footer">
        This is a computer-generated document and does not require a signature.<br>
        AIHRM - Advanced AI Human Resource Management System
    </div>
</body>
</html>
