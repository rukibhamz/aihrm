<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalChain extends Model
{
    protected $guarded = [];

    public function steps()
    {
        return $this->hasMany(ApprovalChainStep::class)->orderBy('step_order');
    }

    public function requests()
    {
        return $this->hasMany(ApprovalRequest::class);
    }
}
