<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReliefOfficerAssigned extends Notification
{
    use Queueable;

    protected $leaveRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Relief Officer Assignment: ' . $this->leaveRequest->user->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->leaveRequest->user->name . ' has assigned you as their relief officer for an upcoming leave request.')
            ->line('Leave Type: ' . $this->leaveRequest->leaveType->name)
            ->line('Dates: ' . $this->leaveRequest->start_date . ' to ' . $this->leaveRequest->end_date)
            ->action('View Leave Request', route('leaves.show', $this->leaveRequest))
            ->line('Please visit the portal to accept or reject this assignment.')
            ->line('Thank you for your cooperation!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'requester_name' => $this->leaveRequest->user->name,
            'leave_type' => $this->leaveRequest->leaveType->name,
            'start_date' => $this->leaveRequest->start_date,
            'end_date' => $this->leaveRequest->end_date,
            'type' => 'relief_officer_assigned',
        ];
    }
}
