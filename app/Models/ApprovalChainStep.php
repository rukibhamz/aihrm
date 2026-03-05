<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalChainStep extends Model
{
    protected $guarded = [];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
    ];

    public function chain()
    {
        return $this->belongsTo(ApprovalChain::class, 'approval_chain_id');
    }
}
