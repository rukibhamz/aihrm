<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAdvance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get pending advance for a specific period
     */
    public static function getPendingAdvance($userId, $month, $year)
    {
        return self::where('user_id', $userId)
            ->where('deduction_month', $month)
            ->where('deduction_year', $year)
            ->where('status', 'pending')
            ->first();
    }

    /**
     * Mark as deducted
     */
    public function markAsDeducted()
    {
        $this->status = 'deducted';
        $this->save();
    }
}
