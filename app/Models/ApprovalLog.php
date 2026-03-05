<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    protected $guarded = [];

    public function request()
    {
        return $this->belongsTo(ApprovalRequest::class, 'approval_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
