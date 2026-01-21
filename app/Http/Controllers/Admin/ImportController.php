<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'type' => 'required|in:employees',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);

        // Basic validation of header
        if ($request->type === 'employees') {
            $required = ['name', 'email', 'department', 'designation', 'join_date'];
            if (count(array_intersect($required, $header)) !== count($required)) {
                return back()->withErrors(['file' => 'Invalid CSV format. Missing required columns: ' . implode(', ', $required)]);
            }
        }

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($data as $row) {
                $row = array_combine($header, $row);
                
                if ($request->type === 'employees') {
                    $this->importEmployee($row);
                    $count++;
                }
            }
            DB::commit();
            return back()->with('success', "Successfully imported {$count} records.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    private function importEmployee($row)
    {
        // Find or create department/designation
        $dept = Department::firstOrCreate(['name' => $row['department']]);
        $desig = Designation::firstOrCreate(['title' => $row['designation']]);

        // Create User
        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make('password'), // Default password
        ]);

        // Create Employee
        Employee::create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'designation_id' => $desig->id,
            'employee_id' => 'EMP-' . Str::upper(Str::random(6)),
            'join_date' => $row['join_date'],
            'status' => 'active',
        ]);
    }
}
