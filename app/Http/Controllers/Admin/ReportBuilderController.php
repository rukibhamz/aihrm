<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportBuilderController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('admin.reports.builder', compact('departments'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'entity' => 'required|in:employees,leaves,payrolls',
            'fields' => 'required|array|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = $this->buildQuery($request);
        $data = $query->get();

        if ($request->action === 'export_csv') {
            return $this->exportCsv($data, $request->fields, $request->entity);
        }

        return view('admin.reports.preview', [
            'data' => $data,
            'fields' => $request->fields,
            'entity' => $request->entity
        ]);
    }

    private function buildQuery(Request $request)
    {
        switch ($request->entity) {
            case 'employees':
                $query = Employee::with(['user', 'department', 'designation']);
                if ($request->department_id) {
                    $query->where('department_id', $request->department_id);
                }
                if ($request->status) {
                    $query->where('status', $request->status);
                }
                if ($request->start_date && $request->end_date) {
                    $query->whereBetween('join_date', [$request->start_date, $request->end_date]);
                }
                return $query;

            case 'leaves':
                $query = LeaveRequest::with(['user', 'leaveType']);
                if ($request->status) {
                    $query->where('status', $request->status);
                }
                if ($request->start_date && $request->end_date) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
                }
                return $query;

            case 'payrolls':
                $query = Payroll::with(['user']);
                if ($request->status) {
                    $query->where('status', $request->status);
                }
                if ($request->start_date && $request->end_date) {
                    // Assuming payrolls have a date or month/year. Using created_at for generic range or specific logic
                    // Better to filter by month/year if provided, but for now generic date range on created_at
                    $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
                return $query;
        }
    }

    private function exportCsv($data, $fields, $entity)
    {
        $filename = $entity . '_report_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($data, $fields) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $fields);

            foreach ($data as $row) {
                $line = [];
                foreach ($fields as $field) {
                    $line[] = $this->getFieldValue($row, $field);
                }
                fputcsv($file, $line);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getFieldValue($row, $field)
    {
        // Handle relationships and custom formatting
        switch ($field) {
            case 'employee_name':
                return $row->user->name ?? $row->name ?? 'N/A'; // Handle both Employee (via user) and User models
            case 'department':
                return $row->department->name ?? 'N/A';
            case 'designation':
                return $row->designation->title ?? 'N/A';
            case 'leave_type':
                return $row->leaveType->name ?? 'N/A';
            case 'net_salary':
                return number_format($row->net_salary, 2);
            case 'basic_salary':
                return number_format($row->basic_salary, 2);
            case 'date':
            case 'join_date':
            case 'start_date':
            case 'end_date':
                return $row->$field ? Carbon::parse($row->$field)->format('Y-m-d') : 'N/A';
            default:
                return $row->$field ?? '';
        }
    }
}
