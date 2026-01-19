<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('attendance:send-reminders')->dailyAt('19:00');
Schedule::command('performance:send-reminders')->dailyAt('09:00');
Schedule::command('leave:carry-forward')->yearlyOn(1, 1, '00:00');
