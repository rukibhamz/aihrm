<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function approvable()
    {
        return $this->morphTo();
    }

    public function chain()
    {
        return $this->belongsTo(ApprovalChain::class, 'approval_chain_id');
    }

    public function logs()
    {
        return $this->hasMany(ApprovalLog::class);
    }

    /**
     * Get the current step definition
     */
    public function currentStep()
    {
        return $this->chain->steps()->where('step_order', $this->current_step_order)->first();
    }
}
