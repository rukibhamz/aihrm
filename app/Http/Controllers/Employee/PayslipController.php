<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Payslip;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipController extends Controller
{
    public function index()
    {
        $payslips = Payslip::with('payroll')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('employee.payslips.index', compact('payslips'));
    }

    public function download(Payslip $payslip)
    {
        // Ensure user owns this payslip
        if ($payslip->user_id !== auth()->id()) {
            abort(403);
        }

        $payslip->load(['user', 'payroll']);

        $pdf = Pdf::loadView('pdf.payslip', compact('payslip'));
        
        $filename = 'Payslip_' . date('F_Y', mktime(0, 0, 0, $payslip->payroll->month, 10)) . '.pdf';
        
        return $pdf->download($filename);
    }
}
