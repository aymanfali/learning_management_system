<?php

namespace App\Notifications;

use App\Models\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignmentGraded extends Notification
{
    use Queueable;
    protected $assignment;

    /**
     * Create a new notification instance.
     */

    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Assignment Has Been Graded')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your assignment for lesson: "' . $this->assignment->lesson->title . '" has been graded.')
            ->line('Grade: ' . $this->assignment->grade)
            ->line('Feedback: ' . ($this->assignment->feedback ?? 'No feedback'))
            ->line('Good Luck!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'assignment_id' => $this->assignment->id,
            'lesson_title' => $this->assignment->lesson->title,
            'grade' => $this->assignment->grade,
            'feedback' => $this->assignment->feedback,
            'message' => 'Your assignment has been graded!'
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
