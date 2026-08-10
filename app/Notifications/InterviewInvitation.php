<?php

namespace App\Notifications;

use App\Models\Application;
use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Application $application,
        protected Interview $interview,
        protected ?string $customBody = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $jobTitle = $this->application->jobPosting->title ?? 'the role';
        $when = optional($this->interview->scheduled_at)->format('l, F j, Y g:i A') ?? 'TBD';
        $where = $this->interview->location_or_link ?: 'Details will be shared shortly';

        $mail = (new MailMessage)
            ->subject('Interview invitation: ' . $jobTitle);

        if (filled($this->customBody)) {
            foreach (preg_split("/\n\n+/", trim($this->customBody)) as $paragraph) {
                $mail->line($paragraph);
            }
        } else {
            $mail->greeting('Hello ' . $this->application->candidate_name . ',')
                ->line("Thank you for applying for {$jobTitle}. We would like to invite you to an interview.")
                ->line("When: {$when}")
                ->line("Where / link: {$where}")
                ->line('Type: ' . ucfirst(str_replace('_', ' ', $this->interview->type ?? 'interview')))
                ->line('Please reply to confirm your availability.');
        }

        return $mail->line('We look forward to speaking with you.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'interview_id' => $this->interview->id,
            'type' => 'interview_invitation',
        ];
    }
}
