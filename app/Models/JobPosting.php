<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    protected $fillable = [
        'title',
        'description',
        'requirements',
        'department',
        'location',
        'job_type',
        'min_salary',
        'max_salary',
        'experience_level',
        'application_deadline',
        'reporting_to',
        'status',
        'created_by',
    ];

    protected $casts = [
        'status' => 'string',
        'application_deadline' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
