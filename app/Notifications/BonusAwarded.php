<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BonusAwarded extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $bonus)
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
        $preference = $notifiable->notificationPreferences()->where('type', 'bonus')->first();

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
                    ->subject('Bonus Awarded!')
                    ->greeting('Congratulations ' . $notifiable->name . '!')
                    ->line('You have been awarded a bonus of ' . number_format($this->bonus->amount, 2) . '.')
                    ->line('Reason: ' . $this->bonus->description)
                    ->action('View Details', route('payslips.index'))
                    ->line('Keep up the great work!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'You received a bonus of ' . number_format($this->bonus->amount, 2),
            'action_url' => route('payslips.index'),
            'type' => 'bonus',
        ];
    }
}
