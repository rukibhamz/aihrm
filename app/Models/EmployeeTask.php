<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTask extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function task()
    {
        return $this->belongsTo(OnboardingTask::class, 'onboarding_task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
