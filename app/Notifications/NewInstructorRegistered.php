<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInstructorRegistered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $instructor;

    public function __construct($instructor)
    {
        $this->instructor = $instructor;
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
            ->subject('New Instructor Registered')
            ->line("A new instructor has registered: {$this->instructor->name}")
            ->line("Email: {$this->instructor->email}");
    }


    /**
     * Database representation.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Instructor Registered',
            'body'  => "Instructor {$this->instructor->name} has registered.",
            'instructor_id' => $this->instructor->id,
            'email' => $this->instructor->email,
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
