<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\User;
use App\Notifications\DocumentExpiryNotification;
use Illuminate\Console\Command;

class SendDocumentExpiryAlerts extends Command
{
    protected $signature = 'hr:document-expiry-alerts {--days=30 : Number of days before expiry to alert}';

    protected $description = 'Send notifications for documents expiring within the specified number of days';

    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->addDays($days);

        $expiringDocuments = Document::whereNotNull('expiry_date')
            ->where('expiry_date', '<=', $cutoffDate)
            ->where('expiry_date', '>=', now())
            ->where('expiry_notified', false)
            ->with('user')
            ->get();

        if ($expiringDocuments->isEmpty()) {
            $this->info('No expiring documents found.');
            return 0;
        }

        $count = 0;
        foreach ($expiringDocuments as $document) {
            // Notify the document owner
            if ($document->user) {
                $document->user->notify(new DocumentExpiryNotification($document));
            }

            // Notify all admin/HR users
            $admins = User::role(['admin', 'hr'])->get();
            foreach ($admins as $admin) {
                if ($admin->id !== $document->user_id) {
                    $admin->notify(new DocumentExpiryNotification($document));
                }
            }

            $document->update(['expiry_notified' => true]);
            $count++;
        }

        $this->info("Sent expiry alerts for {$count} document(s).");
        return 0;
    }
}
