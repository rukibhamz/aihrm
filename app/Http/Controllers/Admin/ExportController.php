<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\LeaveRequest;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $type = $request->type;
        
        switch ($type) {
            case 'employees':
                return $this->exportEmployees();
            case 'payroll':
                return $this->exportPayroll();
            case 'leaves':
                return $this->exportLeaves();
            default:
                return back()->withErrors(['type' => 'Invalid export type']);
        }
    }

    private function exportEmployees()
    {
        $data = Employee::with(['user', 'department', 'designation'])->get();
        $filename = 'employees_' . date('Y-m-d') . '.csv';
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Employee ID', 'Name', 'Email', 'Department', 'Designation', 'Status', 'Join Date']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->employee_id,
                    $row->user->name ?? '',
                    $row->user->email ?? '',
                    $row->department->name ?? '',
                    $row->designation->title ?? '',
                    $row->status,
                    $row->join_date,
                ]);
            }
            fclose($file);
        };

        return $this->streamCsv($callback, $filename);
    }

    private function exportPayroll()
    {
        $data = Payroll::with('user')->get();
        $filename = 'payroll_history_' . date('Y-m-d') . '.csv';

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Employee', 'Month', 'Year', 'Basic Salary', 'Net Salary', 'Status', 'Payment Date']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->user->name ?? '',
                    $row->month,
                    $row->year,
                    $row->basic_salary,
                    $row->net_salary,
                    $row->status,
                    $row->payment_date,
                ]);
            }
            fclose($file);
        };

        return $this->streamCsv($callback, $filename);
    }

    private function exportLeaves()
    {
        $data = LeaveRequest::with(['user', 'leaveType'])->get();
        $filename = 'leave_history_' . date('Y-m-d') . '.csv';

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Employee', 'Type', 'Start Date', 'End Date', 'Days', 'Status', 'Reason']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->user->name ?? '',
                    $row->leaveType->name ?? '',
                    $row->start_date,
                    $row->end_date,
                    $row->duration,
                    $row->status,
                    $row->reason,
                ]);
            }
            fclose($file);
        };

        return $this->streamCsv($callback, $filename);
    }

    private function streamCsv($callback, $filename)
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        return response()->stream($callback, 200, $headers);
    }
}
