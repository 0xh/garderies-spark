<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionWelcome extends Notification implements ShouldQueue
{
    use Queueable;

    var $plan;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($plan)
    {
        $this->plan = $plan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Merci pour votre abonnement !")
            ->line("Nous vous remercions pour votre souscription au plan " . $this->plan->name . " !")
            ->line("Vous avez désormais accès à toutes les fonctionnalités de l'application Garderies, selon votre abonnement.");
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

    public function tags()
    {
        return ['notification', 'email', 'type:subscription-welcome'];
    }
}
