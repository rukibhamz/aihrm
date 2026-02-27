<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveTypeGrade extends Model
{
    protected $guarded = [];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }
}
