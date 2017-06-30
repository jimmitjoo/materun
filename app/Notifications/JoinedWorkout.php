<?php

namespace App\Notifications;

use App\Workout;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JoinedWorkout extends Notification
{
    use Queueable;

    public $workout;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Workout $workout)
    {
        $this->workout = $workout;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->success()
            ->line(__('workout.someone_joined_your_workout'))
            ->action(__('workout.your_workout'), route('workout', ['id' => $this->workout->id]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
