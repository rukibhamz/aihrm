<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimePolicy extends Model
{
    protected $fillable = ['name', 'standard_daily_hours', 'weekday_multiplier', 'weekend_multiplier', 'holiday_multiplier', 'is_active'];
}
