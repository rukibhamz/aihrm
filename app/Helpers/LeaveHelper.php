<?php

namespace App\Helpers;

use App\Models\Setting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveHelper
{
    /**
     * Calculate the number of working days between two dates
     *
     * @param string|Carbon $startDate
     * @param string|Carbon $endDate
     * @return int
     */
    public static function calculateWorkingDays($startDate, $endDate): int
    {
        $start = $startDate instanceof Carbon ? $startDate : Carbon::parse($startDate);
        $end = $endDate instanceof Carbon ? $endDate : Carbon::parse($endDate);
        
        // Get working days from settings (default: Monday-Friday = 1-5)
        $workingDays = json_decode(Setting::get('working_days', '[1,2,3,4,5]'), true);
        
        // Create period between dates (inclusive)
        $period = CarbonPeriod::create($start, $end);
        
        $workingDaysCount = 0;
        
        foreach ($period as $date) {
            // Carbon's dayOfWeekIso: 1 (Monday) to 7 (Sunday)
            if (in_array($date->dayOfWeekIso, $workingDays)) {
                $workingDaysCount++;
            }
        }
        
        return $workingDaysCount;
    }
    
    /**
     * Get the working days configuration
     *
     * @return array
     */
    public static function getWorkingDays(): array
    {
        return json_decode(Setting::get('working_days', '[1,2,3,4,5]'), true);
    }
    
    /**
     * Check if a specific date is a working day
     *
     * @param string|Carbon $date
     * @return bool
     */
    public static function isWorkingDay($date): bool
    {
        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);
        $workingDays = self::getWorkingDays();
        
        return in_array($carbonDate->dayOfWeekIso, $workingDays);
    }
}
