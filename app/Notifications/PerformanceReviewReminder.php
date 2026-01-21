<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PerformanceReviewReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $review)
    {
        //
    }

    public function via(object $notifiable): array
    {
        $channels = [];
        $preference = $notifiable->notificationPreferences()->where('type', 'performance')->first();

        if (!$preference || $preference->database_enabled) {
            $channels[] = 'database';
        }
        if (!$preference || $preference->email_enabled) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Upcoming Performance Review')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('This is a reminder that you have a performance review scheduled for ' . $this->review->scheduled_date->format('F d, Y') . '.')
                    ->line('Please ensure you have completed any necessary self-assessments.')
                    ->action('View Review Details', route('performance.reviews.show', $this->review->id))
                    ->line('Good luck!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Performance review scheduled for ' . $this->review->scheduled_date->format('M d'),
            'action_url' => route('performance.reviews.show', $this->review->id),
            'type' => 'performance_reminder',
        ];
    }
}
