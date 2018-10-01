<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Spark\Notifications\SparkChannel;
use Laravel\Spark\Notifications\SparkNotification;

class BookingReminder extends Notification implements ShouldQueue
{
    use Queueable;

    var $booking;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $user           = $this->booking->substitute;
        $via            = [SparkChannel::class];
        $preferences    = ($user->contact_preferences) ? $user->contact_preferences : [];

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
            ->subject('Rappel de remplacement')
            ->markdown('emails.bookingReminder', ['bookingRequest' => $this->booking]);
    }

    /**
     * @param $notifiable
     * @return SparkNotification
     */
    public function toSpark($notifiable)
    {
        return (new SparkNotification)
            ->icon('fa-user-clock')
            ->body('Rappel de remplacement')
            ->action('Voir la demande', route('bookings.show', $this->booking));
    }

    public function toNexmo($notifiable)
    {
        $date       = $this->booking->start->format('d.m.Y');
        $start      = $this->booking->start->format('H:i');
        $end        = $this->booking->end->format('H:i');
        $nursery    = $this->booking->nursery->name;
        $user       = $this->booking->user->name;

        return (new NexmoMessage)
            ->content('Rappel de remplacement pour le ' . $date . ', de ' . $start . ' à ' . $end . ', dans la garderie ' . $nursery);
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
        return ['notification', 'sms', 'spark', 'email', 'type:booking-reminder'];
    }
}
