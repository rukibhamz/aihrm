<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Notifications\AttendanceReminder;
use Carbon\Carbon;

class SendAttendanceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to employees who haven\'t clocked out after 9 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for attendance reminders...');

        $today = Carbon::today();
        $nineHoursAgo = Carbon::now()->subHours(9);

        $attendances = Attendance::where('date', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->where('clock_in', '<=', $nineHoursAgo) // Clocked in more than 9 hours ago
            ->with('user')
            ->get();

        $count = 0;
        foreach ($attendances as $attendance) {
            if ($attendance->user) {
                $attendance->user->notify(new AttendanceReminder($attendance));
                $count++;
            }
        }

        $this->info("Sent {$count} attendance reminders.");
    }
}
