<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Notifications\Notifiable;

class Application extends Model
{
    use Notifiable;

    protected $fillable = [
        'job_posting_id',
        'candidate_name',
        'candidate_email',
        'candidate_phone',
        'resume_path',
        'ai_score',
        'status',
        'notes', // Added for internal HR comments
    ];

    protected $casts = [
        'ai_score' => 'integer',
        'status' => 'string',
    ];

    const STATUS_APPLIED = 'applied';
    const STATUS_SCREENING = 'screening';
    const STATUS_INTERVIEW = 'interview';
    const STATUS_OFFER = 'offer';
    const STATUS_HIRED = 'hired';
    const STATUS_REJECTED = 'rejected';

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function resumeAnalysis(): HasOne
    {
        return $this->hasOne(ResumeAnalysis::class);
    }

    public function routeNotificationForMail($notification)
    {
        return $this->candidate_email;
    }
}
