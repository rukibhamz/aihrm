<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope for today's attendance
    public function scopeToday($query)
    {
        return $query->where('date', Carbon::today());
    }

    // Scope for this month
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', Carbon::now()->month)
                     ->whereYear('date', Carbon::now()->year);
    }
    
    // Calculate duration in hours
    public function getDurationAttribute()
    {
        if ($this->clock_in && $this->clock_out) {
            return $this->clock_in->diffInHours($this->clock_out);
        }
        return 0;
    }
}
