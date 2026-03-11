<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeerFeedbackRequest extends Model
{
    protected $fillable = [
        'requester_id',
        'target_user_id',
        'context',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function responses()
    {
        return $this->hasMany(PeerFeedbackResponse::class, 'request_id');
    }
}
