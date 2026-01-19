<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
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
     * Get bonuses for a specific month/year
     */
    public static function forPeriod($userId, $month, $year)
    {
        return self::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->sum('amount');
    }
}
