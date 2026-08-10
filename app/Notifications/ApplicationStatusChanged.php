<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    protected ?string $customBody;

    public function __construct($application, ?string $customBody = null)
    {
        $this->application = $application;
        $this->customBody = $customBody;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $jobTitle = $this->application->jobPosting->title ?? 'the position';
        $status = strtoupper((string) $this->application->status);

        $mail = (new MailMessage)
            ->subject('Update on your application for ' . $jobTitle);

        if (filled($this->customBody)) {
            foreach (preg_split("/\n\n+/", trim($this->customBody)) as $paragraph) {
                $mail->line($paragraph);
            }

            return $mail;
        }

        if ($this->application->status === 'rejected') {
            return $mail
                ->greeting('Hello ' . ($this->application->candidate_name ?? '') . ',')
                ->line("Thank you for your interest in {$jobTitle}.")
                ->line('After careful review, we will not be moving forward with your application at this time.')
                ->line('We appreciate the time you invested and wish you every success.');
        }

        if ($this->application->status === 'interview') {
            return $mail
                ->greeting('Hello ' . ($this->application->candidate_name ?? '') . ',')
                ->line("Your application for {$jobTitle} has progressed to the interview stage.")
                ->line('Our recruiting team will contact you with next steps shortly.');
        }

        return $mail
            ->line('Your application status has been updated to: ' . $status)
            ->line('Thank you for your interest in joining our team!')
            ->action('View open roles', route('jobs.index'))
            ->line('We will be in touch soon.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'job_title' => $this->application->jobPosting->title ?? null,
            'status' => $this->application->status,
            'message' => 'Your application for ' . ($this->application->jobPosting->title ?? 'a role') . ' is now ' . $this->application->status,
        ];
    }
}
