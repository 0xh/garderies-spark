<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Spark\Notifications\SparkChannel;
use Laravel\Spark\Notifications\SparkNotification;

class UserTrialEnding extends Notification implements ShouldQueue
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SparkChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $trial_end = $notifiable->trial_ends_at->format('d.m.Y');

        return (new MailMessage)
            ->error()
            ->subject("Votre période d'essai arrive au bout !")
            ->line("Votre période d'essai de notre application arrive à échéance le : ")
            ->line("**" . $trial_end . "**")
            ->line("Nous vous invitons à parcourir nos abonnements avantageux afin de vous permettre de continuer à profiter pleinement des service de l'application Garderies.")
            ->line("N'hésitez pas à nous faire part de vos questions / suggestions.")
            ->action("Voir les abonnements", url('/settings#/subscription'))
            ->line("Merci de votre confiance !");
    }

    public function toSpark($notifiable)
    {
        return (new SparkNotification)
            ->icon('fa-info')
            ->body("Votre période d'essai arrive à échéance ! Découvrez quel abonnement est le plus adapté à vos besoins.")
            ->action('Voir les abonnements', '/settings#/subscription');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
