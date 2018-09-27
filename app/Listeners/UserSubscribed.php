<?php

namespace App\Listeners;

use App\Notifications\SubscriptionWelcome;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        }
    }
}
