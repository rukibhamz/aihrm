<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayrollGenerated extends Notification
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayrollGenerated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $payslip)
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
        $preference = $notifiable->notificationPreferences()->where('type', 'payroll')->first();

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
                    ->subject('Payslip Generated - ' . $this->payslip->payroll->month . '/' . $this->payslip->payroll->year)
                    ->line('Your payslip for ' . date('F', mktime(0, 0, 0, $this->payslip->payroll->month, 10)) . ' ' . $this->payslip->payroll->year . ' has been generated.')
                    ->line('Net Salary: ' . number_format($this->payslip->net_salary, 2))
                    ->action('View Payslip', route('payslips.show', $this->payslip->id))
                    ->line('Thank you for your hard work!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Payslip generated for ' . date('F', mktime(0, 0, 0, $this->payslip->payroll->month, 10)) . ' ' . $this->payslip->payroll->year,
            'action_url' => route('payslips.show', $this->payslip->id),
            'type' => 'payroll',
        ];
    }
}
