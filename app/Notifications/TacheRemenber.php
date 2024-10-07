<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TacheRemenber extends Notification
{
    use Queueable;

    protected $tache;

    /**
     * Create a new notification instance.
     */
    public function __construct($tache)
    {
        $this->tache = $tache;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Rappel : Vous avez une tâche non completée.')
                    ->action('Voir la tâche', url('/tache/ . $this->tache->id'))
                    ->line('Merci d\'avoir utilisé notre application !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
