<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Spark\Notifications\SparkChannel;
use Laravel\Spark\Notifications\SparkNotification;

class UserWelcome extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SparkChannel::class];
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
            ->subject('Bienvenue sur Garderies')
            ->line("Nous vous remercions d'avoir créé un compte sur Garderies.ch !")
            ->line("L'application en ligne vous permettra de gagner un temps considérable que vous soyez un employé ou un responsable de structure d'accueil.")
            ->action("Voir mon compte", url('/'))
            ->line("Notre équipe se tient à votre disposition en cas de questions.");
    }

    public function toSpark($notifiable)
    {
        return (new SparkNotification)
            ->icon('fa-info')
            ->body('Bienvenue sur Garderies ! Découvrez comment optimiser votre temps de travail avec notre documentation en ligne.')
            ->action('Documentation', '/docs');
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
