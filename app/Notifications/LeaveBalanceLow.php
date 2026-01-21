<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveBalanceLow extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $leaveBalance, public $remainingDays)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];
        $preference = $notifiable->notificationPreferences()->where('type', 'leave')->first();

        // Default to both if no preference set
        if (!$preference || $preference->database_enabled) {
            $channels[] = 'database';
        }
        if (!$preference || $preference->email_enabled) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Leave Balance Low')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your leave balance is running low.')
                    ->line('Remaining Leave Days: ' . $this->remainingDays)
                    ->line('Please plan your leaves accordingly.')
                    ->action('View Leave Balance', route('leaves.index'))
                    ->line('Contact HR if you have any questions.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your leave balance is low (' . $this->remainingDays . ' days remaining)',
            'action_url' => route('leaves.index'),
            'type' => 'leave',
        ];
    }
}
