<?php

namespace App\Traits;

use App\Models\ApprovalChain;
use App\Models\ApprovalRequest;

trait HasApprovals
{
    /**
     * Get the polymorphic approval requests for this model.
     */
    public function approvalRequests()
    {
        return $this->morphMany(ApprovalRequest::class, 'approvable');
    }

    /**
     * Get the currently active approval request (pending).
     */
    public function currentApproval()
    {
        return $this->approvalRequests()->where('status', 'pending')->latest()->first();
    }

    /**
     * Submit this model for approval based on its matching chain.
     */
    public function submitForApproval()
    {
        // Find if a chain exists for this model type
        $chain = ApprovalChain::where('module_type', static::class)->first();

        // If no chain exists, we just instantly return true (auto-approve)
        // or throw an exception depending on business rules.
        // For AIHRM, if no chain exists, it implies no approval required.
        if (!$chain) {
            return false;
        }

        // Create the approval request record starting at step 1
        return $this->approvalRequests()->create([
            'approval_chain_id' => $chain->id,
            'current_step_order' => 1,
            'status' => 'pending'
        ]);
    }
}
