<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDeduction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get active loan deduction for a user
     */
    public static function getActiveDeduction($userId)
    {
        return self::where('user_id', $userId)
            ->where('status', 'active')
            ->where('remaining_balance', '>', 0)
            ->first();
    }

    /**
     * Process monthly deduction
     */
    public function processDeduction()
    {
        $deductionAmount = min($this->monthly_deduction, $this->remaining_balance);
        
        $this->remaining_balance -= $deductionAmount;
        
        if ($this->remaining_balance <= 0) {
            $this->status = 'completed';
        }
        
        $this->save();
        
        return $deductionAmount;
    }
}
