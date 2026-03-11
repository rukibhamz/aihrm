<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentExpiryNotification extends Notification
{
    use Queueable;

    public function __construct(public Document $document)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $daysLeft = now()->diffInDays($this->document->expiry_date, false);
        $urgency = $daysLeft <= 7 ? 'URGENT: ' : '';

        return (new MailMessage)
            ->subject($urgency . 'Document Expiring Soon: ' . $this->document->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("The following document is expiring in {$daysLeft} day(s):")
            ->line("**Document:** {$this->document->title}")
            ->line("**Type:** {$this->document->type}")
            ->line("**Expiry Date:** {$this->document->expiry_date->format('M d, Y')}")
            ->action('View Documents', url('/admin/documents'))
            ->line('Please ensure this document is renewed before it expires.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Document Expiring Soon',
            'message' => "Document \"{$this->document->title}\" expires on {$this->document->expiry_date->format('M d, Y')}.",
            'document_id' => $this->document->id,
            'type' => 'document_expiry',
            'url' => '/admin/documents',
        ];
    }
}
