<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PerformanceReview;
use App\Notifications\PerformanceReviewReminder;
use Carbon\Carbon;

class SendPerformanceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for upcoming performance reviews (3 days in advance)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for performance review reminders...');

        $targetDate = Carbon::today()->addDays(3);

        $reviews = PerformanceReview::whereDate('scheduled_date', $targetDate)
            ->where('status', 'scheduled') // Assuming 'scheduled' is a valid status
            ->with('employee.user')
            ->get();

        $count = 0;
        foreach ($reviews as $review) {
            if ($review->employee && $review->employee->user) {
                $review->employee->user->notify(new PerformanceReviewReminder($review));
                $count++;
            }
        }

        $this->info("Sent {$count} performance review reminders.");
    }
}
