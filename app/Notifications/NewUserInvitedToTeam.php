<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserInvitedToTeam extends Notification implements ShouldQueue
{
    use Queueable;

    private $invitation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invitation)
    {
        $this->invitation = $invitation;
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
                    ->subject("Vous êtes invité à rejoindre Garderies.ch")
                    ->line("Un administrateur de réseau de Garderies.ch vous invite à rejoindre l'équipe " . $this->invitation->team->name . '.')
                    ->line("Une fois inscrit vous pourrez créer votre profil, déclarer vos disponibilités et profiter d'un réseau de remplaçants.")
                    ->action("Voir l'invitation", url('register?invitation='.$this->invitation->token) )
                    ->line('Merci de votre confiance !');
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
        return ['notification', 'email', 'type:team-invite-new'];
    }
}
