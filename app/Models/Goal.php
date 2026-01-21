<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type', // text, metric
        'status',
        'progress',
        'due_date',
        'target_value',
        'current_value',
        'unit',
        'weight',
    ];

    protected $casts = [
        'due_date' => 'date',
        'target_value' => 'float',
        'current_value' => 'float',
        'weight' => 'integer',
        'progress' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate process percentage for metric-based goals
     */
    public function calculateProgress()
    {
        if ($this->type === 'text' || !$this->target_value || $this->target_value == 0) {
            return $this->progress;
        }

        $percentage = ($this->current_value / $this->target_value) * 100;
        return min(100, max(0, round($percentage)));
    }

    /**
     * Get traffic light color based on achievement
     */
    public function getTrafficLightAttribute()
    {
        $progress = $this->type === 'metric' ? $this->calculateProgress() : $this->progress;

        // Green: 100% or completed
        if ($progress >= 100 || $this->status === 'completed') {
            return 'green';
        }

        // Yellow: 70-99% on track
        if ($progress >= 70) {
            return 'yellow';
        }

        // Red: < 70% needs attention
        return 'red';
    }

    public function getProgressColorAttribute()
    {
        return match($this->traffic_light) {
            'green' => 'text-green-600 bg-green-100',
            'yellow' => 'text-yellow-600 bg-yellow-100',
            'red' => 'text-red-600 bg-red-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }
}
