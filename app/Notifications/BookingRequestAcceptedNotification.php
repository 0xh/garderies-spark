<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Spark\Notifications\SparkChannel;
use Laravel\Spark\Notifications\SparkNotification;

class BookingRequestAcceptedNotification extends Notification implements ShouldQueue
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
        $date       = $this->bookingRequest->start->format('d.m.Y');
        $nursery    = $this->bookingRequest->nursery->name;

        return (new MailMessage)
            ->subject("Demande de remplacement acceptée")
            ->line("La demande de remplacement pour la date du " . $date . " à la garderie " . $nursery . " a été validée.")
            ->action('Voir la demande de remplacement', route('booking-requests.show', $this->bookingRequest))
            ->line('Merci de votre confiance !');
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function toSpark($notifiable)
    {
        return (new SparkNotification)
            ->icon('fa-check')
            ->body('Demande de remplacement validée !')
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
