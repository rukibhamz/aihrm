<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOffboardingTask extends Model
{
    protected $fillable = [
        'resignation_id',
        'offboarding_task_id',
        'is_completed',
        'comments',
        'completed_by',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function resignation()
    {
        return $this->belongsTo(Resignation::class);
    }

    public function task()
    {
        return $this->belongsTo(OffboardingTask::class, 'offboarding_task_id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
