<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\NexmoMessage;
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
        $user           = $this->bookingRequest->substitute;
        $preferences    = ($user->contact_preferences) ? $user->contact_preferences : [];
        $via            = [SparkChannel::class];

        if (count($preferences)) {
            foreach ($preferences as $preference) {
                switch ($preference) {
                    case 'email':
                        $via[] = 'mail';
                        break;
                    case 'sms':
                        //$via[] = 'nexmo';
                        break;
                }
            }
        }
        
        return $via;
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
            ->subject('Nouvelle demande de remplacement')
            ->markdown('emails.bookingRequest', ['bookingRequest' => $this->bookingRequest]);
    }

    /**
     * @param $notifiable
     * @return SparkNotification
     */
    public function toSpark($notifiable)
    {
        return (new SparkNotification)
            ->icon('fa-user-clock')
            ->body('Nouvelle demande de remplacement !')
            ->action('Voir la demande', route('booking-requests.show', $this->bookingRequest));
    }

    public function toNexmo($notifiable)
    {
        $date       = $this->bookingRequest->start->format('d.m.Y');
        $start      = $this->bookingRequest->start->format('H:i');
        $end        = $this->bookingRequest->end->format('H:i');
        $nursery    = $this->bookingRequest->nursery->name;
        $user       = $this->bookingRequest->user->name;

        return (new NexmoMessage)
            ->content('Demande de remplacement pour le ' . $date . ', de ' . $start . ' à ' . $end . ', dans la garderie ' . $nursery . '.');
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
        return ['notification', 'booking-request'];
    }
}
