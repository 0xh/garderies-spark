<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ExistingUserInvitedToTeam extends Notification implements ShouldQueue
{
    use Queueable;

    private $team;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($team)
    {
        $this->team = $team;
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
                    ->subject("Vous êtes invité dans une équipe")
                    ->line("Vous êtes invité à rejoindre l'équipe \"" . $this->team->name . "\" sur l'application Garderies.ch.")
                    ->line("Cliquez simplement sur le bouton ci-dessous pour voir l'invitation et accepter d'intégrer l'équipe.")
                    ->action("Voir l'invitation", url('/settings#/'. \Spark::teamsPrefix()) )
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
        return ['notification', 'email', 'type:team-invite-existing'];
    }
}
