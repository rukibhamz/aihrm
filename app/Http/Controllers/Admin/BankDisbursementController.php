<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankDisbursementController extends Controller
{
    public function download(Request $request, \App\Models\Payroll $payroll)
    {
        $fileName = 'disbursement_file_' . $payroll->month . '_' . $payroll->year . '.csv';
        $payslips = $payroll->payslips()->with('user.employee')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['S/N', 'Employee Name', 'Bank Name', 'Account Number', 'Net Pay (NGN)'];

        $callback = function() use($payslips, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $count = 1;
            foreach ($payslips as $payslip) {
                // Assuming employee has bank details
                $bankName = $payslip->user->employee->bank_name ?? 'N/A';
                $accountNumber = $payslip->user->employee->account_number ?? 'N/A';

                fputcsv($file, [
                    $count++,
                    $payslip->user->name,
                    $bankName,
                    $accountNumber,
                    $payslip->net_salary
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
