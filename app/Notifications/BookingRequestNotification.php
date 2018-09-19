<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Spark\Notifications\SparkChannel;
use Laravel\Spark\Notifications\SparkNotification;

class BookingRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    var $bookingRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bookingRequest)
    {
        $this->bookingRequest = $bookingRequest;
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
        return (new MailMessage)
            ->line("Vous avez reçu une nouvelle demande de remplacement (voir ci-dessous). Connectez-vous à votre compte Garderies.ch afin de voir le détail.")
            ->line($this->bookingRequest->start->format('d.m.Y H:i') . " à " . $this->bookingRequest->end->format('d.m.Y H:i'))
            ->action('Mon compte Garderies.ch', route('login'))
            ->line('Merci de votre confiance !');
    }

    public function toSpark($notifiable)
    {
        return (new SparkNotification)
            ->icon('fa-user-clock')
            ->body('Nouvelle demande de remplacement !')
            ->action('Voir la demande', route('booking-requests.show', $this->bookingRequest));
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
