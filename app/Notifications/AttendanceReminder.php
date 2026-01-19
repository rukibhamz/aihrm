<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $attendance)
    {
        //
    }

    public function via(object $notifiable): array
    {
        $channels = [];
        $preference = $notifiable->notificationPreferences()->where('type', 'system')->first(); // Using 'system' or maybe 'attendance' if I added it? 
        // I used 'payroll', 'leave', 'bonus', 'loan', 'system'. 
        // Let's use 'system' for now or add 'attendance' to types.
        // Actually, 'system' is fine, or I can add 'attendance' to the types list in controller.
        // Let's stick to 'system' for simplicity, or better, add 'attendance' to be precise.
        // I'll add 'attendance' to the controller types later. For now, let's use 'system' as fallback or just 'attendance' and expect the user to configure it if I add it.
        // Let's use 'attendance' and I will update the controller to include it.
        
        $preference = $notifiable->notificationPreferences()->where('type', 'attendance')->first();

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
                    ->subject('Action Required: Clock Out Reminder')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Our records show that you clocked in at ' . $this->attendance->clock_in->format('H:i') . ' today but haven\'t clocked out yet.')
                    ->line('Please clock out to ensure your attendance is recorded correctly.')
                    ->action('Clock Out Now', route('attendance.index'))
                    ->line('If you have already left, please contact your manager.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'You haven\'t clocked out for today yet.',
            'action_url' => route('attendance.index'),
            'type' => 'attendance_reminder',
        ];
    }
}
