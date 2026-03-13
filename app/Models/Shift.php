<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'grace_period_minutes',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
