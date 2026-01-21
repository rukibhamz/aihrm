<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $loan)
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
        $preference = $notifiable->notificationPreferences()->where('type', 'loan')->first();

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
                    ->subject('Loan Request Approved')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your loan request has been approved!')
                    ->line('Loan Amount: ' . number_format($this->loan->loan_amount, 2))
                    ->line('Monthly Deduction: ' . number_format($this->loan->monthly_deduction, 2))
                    ->line('Start Date: ' . $this->loan->start_date->format('F d, Y'))
                    ->action('View Details', route('admin.loans.index'))
                    ->line('The monthly deduction will be reflected in your upcoming payslips.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your loan request for ' . number_format($this->loan->loan_amount, 2) . ' has been approved',
            'action_url' => route('admin.loans.index'),
            'type' => 'loan',
        ];
    }
}
