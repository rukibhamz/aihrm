<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeerFeedbackResponse extends Model
{
    protected $fillable = [
        'request_id',
        'reviewer_id',
        'strengths',
        'areas_for_improvement',
        'collaboration_rating',
        'communication_rating',
        'additional_comments',
    ];

    public function request()
    {
        return $this->belongsTo(PeerFeedbackRequest::class, 'request_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
