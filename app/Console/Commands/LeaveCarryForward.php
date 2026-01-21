<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\LeaveBalance;
use App\Models\LeaveType;

class LeaveCarryForward extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:carry-forward {--year= : The year to process carry forward for (defaults to current year)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process year-end leave balance carry forward';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->option('year') ?? date('Y');
        $this->info("Processing leave carry forward for year {$year}...");

        $annualLeaveType = LeaveType::where('name', 'Annual Leave')->first();
        if (!$annualLeaveType) {
            $this->error('Annual Leave type not found.');
            return;
        }

        $users = User::where('status', 'active')->get();
        $maxCarryForward = 5; // Configurable limit

        $count = 0;
        foreach ($users as $user) {
            $balance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $annualLeaveType->id)
                ->first();

            if ($balance) {
                $remaining = $balance->total_days - $balance->used_days;
                $carryForward = min($remaining, $maxCarryForward);
                
                // Logic: In a real system, we might create a new balance record for the next year
                // or add to a 'carried_over' column.
                // For simplicity, let's assume we are resetting for the new year and adding to total_days.
                // Or better, just log it for now as "processed" since we don't have a 'year' column in leave_balances table yet.
                // Wait, leave_balances table structure: user_id, leave_type_id, total_days, used_days.
                // It doesn't have a year. This implies it's a running balance or reset manually.
                
                // Let's assume we reset used_days to 0 and set total_days to (Base + CarryForward).
                // Base annual leave = 20 (example).
                
                $baseAnnualLeave = 20;
                $newTotal = $baseAnnualLeave + ($carryForward > 0 ? $carryForward : 0);
                
                $balance->update([
                    'total_days' => $newTotal,
                    'used_days' => 0
                ]);
                
                $this->line("User {$user->name}: Carried forward {$carryForward} days. New Balance: {$newTotal}");
                $count++;
            }
        }

        $this->info("Processed carry forward for {$count} employees.");
    }
}
