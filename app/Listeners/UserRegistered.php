<?php

namespace App\Listeners;

use App\Notifications\UserWelcome;
use App\Team;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class UserRegistered
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

        $user->notify(new UserWelcome());

        if (!$user->hasTeams() && $user->trial_ends_at != null) {
            $team_names = [
                'Alexandrite', 'Améthyste', 'Aigue-Marine', 'Aventurine', 'Calcédoine', 'Citrine',
                'Cornaline', 'Diamant', 'Grenat', 'Iolite', 'Kyanite', 'Nacre', 'Onyx', 'Opale',
                'Péridot', 'Perle', 'Pyrite', 'Quartz', 'Spinelle', 'Tanzanite', 'Topaze', 'Tourmaline',
                'Turquoise', 'Agate', 'Ambre', 'Jade', 'Pierre de lune', 'Pierre de soleil', 'Serpentine'
            ];

            $team_name = $team_names[rand(0, (count($team_names) - 1))] . ' ' . $user->id;

            $team               = new Team();
            $team->owner_id     = $user->id;
            $team->name         = $team_name;
            $team->save();

            DB::table('team_users')->insert(['team_id' => $team->id, 'user_id' => $user->id, 'role' => 'owner']);
        }

    }
}
