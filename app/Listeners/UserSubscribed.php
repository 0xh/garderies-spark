<?php

namespace App\Listeners;

use App\Notifications\SubscriptionWelcome;
use App\Team;
use App\User;
use App\Notifications\UserSubscribed as UserSubscribeNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Laravel\Spark\Spark;

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
        $user = $event->user;

        // store the plan object
        $sparkPlan = $event instanceof SubscriptionCancelled ? null : $user->sparkPlan();
        // notify the user
        if ($sparkPlan) {
            $user->notify(new SubscriptionWelcome($sparkPlan));

            // notify the admins
            $users = User::find([1,2]);
            Notification::send($users, new UserSubscribeNotification($event->user, $sparkPlan));
        }
    }
}
