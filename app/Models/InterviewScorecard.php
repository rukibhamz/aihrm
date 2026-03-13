<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewScorecard extends Model
{
    protected $fillable = [
        'interview_id',
        'criteria_scores',
        'total_score',
        'recommendation',
        'strengths',
        'weaknesses',
        'comments',
    ];

    protected $casts = [
        'criteria_scores' => 'array',
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
}
