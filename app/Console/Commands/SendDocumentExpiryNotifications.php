<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Notifications\DocumentExpiring;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDocumentExpiryNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for documents expiring soon (30, 7, 1 day) and notify users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring documents...');

        $thresholds = [30, 7, 1];

        foreach ($thresholds as $days) {
            $targetDate = Carbon::now()->addDays($days)->format('Y-m-d');
            
            $documents = Document::whereDate('expiry_date', $targetDate)
                ->where('status', 'active')
                ->with('user')
                ->get();

            foreach ($documents as $document) {
                if ($document->user) {
                    $document->user->notify(new DocumentExpiring($document, $days));
                    $this->info("Notified {$document->user->name} about '{$document->title}' expiring in {$days} days.");
                }
            }
        }

        // Also check for expired today
        $expiredDocs = Document::whereDate('expiry_date', Carbon::today())
             ->where('status', 'active')
             ->get();

        foreach ($expiredDocs as $doc) {
             $doc->update(['status' => 'expired']);
             if ($doc->user) {
                 $doc->user->notify(new DocumentExpiring($doc, 0));
                 $this->info("Marked '{$doc->title}' as expired.");
             }
        }    

        $this->info('Done.');
    }
}
