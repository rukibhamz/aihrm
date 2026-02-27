<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function requests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function balances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function grades()
    {
        return $this->hasMany(LeaveTypeGrade::class);
    }

    public function employmentStatuses()
    {
        return $this->belongsToMany(EmploymentStatus::class, 'leave_type_employment_status');
    }

    public function getDaysAllowedForUser(User $user)
    {
        $employee = $user->employee;
        
        if (!$employee || !$employee->employment_status_id) {
            // Unmapped employees get 0 days
            return 0; 
        }

        // If specific employment statuses are configured, enforce eligibility
        if ($this->employmentStatuses()->count() > 0) {
            if (!$this->employmentStatuses->contains('id', $employee->employment_status_id)) {
                return 0; // Not eligible
            }
        }

        // Check if there is a grade override
        if ($employee->grade_level_id) {
            $gradeOverride = $this->grades->where('grade_level_id', $employee->grade_level_id)->first();
            if ($gradeOverride) {
                return $gradeOverride->days_allowed;
            }
        }

        // Fallback to the base days allowed
        return $this->days_allowed;
    }
}
