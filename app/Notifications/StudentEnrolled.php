<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentEnrolled extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $student;
    public $course;

    public function __construct($student, $course)
    {
        $this->student = $student;
        $this->course = $course;
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
            ->subject('New Student Enrolled')
            ->line("{$this->student->name} has enrolled in your course '{$this->course->title}'.")
            ->action('View Course', url("/courses/{$this->course->id}"));
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Student Enrolled',
            'body' => "{$this->student->name} has enrolled in your course '{$this->course->title}'.",
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
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
