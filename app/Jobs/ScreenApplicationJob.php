<?php

namespace App\Jobs;

use App\Models\Application;
use App\Services\AtsAutomationService;
use App\Services\Ai\AiConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScreenApplicationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 120;

    public function __construct(public int $applicationId) {}

    public function handle(AtsAutomationService $ats): void
    {
        if (! AiConfig::autoScreen()) {
            Log::info("AI auto-screen disabled; skipping application {$this->applicationId}");

            return;
        }

        $application = Application::with('jobPosting')->find($this->applicationId);
        if (! $application) {
            return;
        }

        $ats->screenApplication($application);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("ScreenApplicationJob failed for #{$this->applicationId}: " . $exception->getMessage());
    }
}
