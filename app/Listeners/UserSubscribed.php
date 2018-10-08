<?php

namespace App\Listeners;

use App\Notifications\SubscriptionWelcome;
use App\User;
use App\Notifications\UserSubscribed as UserSubscribeNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class UserSubscribed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // store the plan object
        $sparkPlan = $event instanceof SubscriptionCancelled ? null : $event->user->sparkPlan();
        // notify the user
        if ($sparkPlan) {
            $event->user->notify(new SubscriptionWelcome($sparkPlan));

            // notify the admins
            $users = User::find([1,2]);
            Notification::send($users, new UserSubscribeNotification($event->user, $sparkPlan));
        }
    }
}
