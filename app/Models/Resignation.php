<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeOffboardingTask; // Added this import

class Resignation extends Model
{
    protected $fillable = [
        'user_id',
        'resignation_date',
        'last_working_day',
        'reason',
        'status',
        'hr_comments',
        'exit_interview_notes',
    ];

    protected $casts = [
        'resignation_date' => 'date',
        'last_working_day' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
