<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeAnalysis extends Model
{
    protected $fillable = [
        'application_id',
        'extracted_data',
        'match_score',
        'ai_feedback',
        'strengths',
        'gaps',
    ];

    protected $casts = [
        'extracted_data' => 'array',
        'match_score' => 'integer',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
