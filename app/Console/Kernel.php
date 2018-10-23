<?php

namespace App\Console;

use App\Booking;
use App\Notifications\AvailabilityReminder;
use App\Notifications\BookingReminder;
use App\Notifications\UserTrialEnding;
use App\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Notification;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // generate the spark KPI
        $schedule->command('spark:kpi')->dailyAt('23:55');

        // remind the user to post availabilities
        $schedule->call(function (){
            $users = User::leftJoin('team_users', 'team_users.user_id', '=', 'users.id')->where('role', '=', 'substitute')->get();
            Notification::send($users, new AvailabilityReminder());
        })->monthlyOn(1);

        // remind users for upcoming bookings
        $schedule->call(function (){

            // retrieve bookings happening in 3 days
            $bookings = Booking::whereDay('start', '=', now()->addDays(3))
                ->where('status', Booking::STATUS_APPROVED)
                ->get();

            // notify the substitutes
            foreach ($bookings as $booking) {
                $user = $booking->substitute;
                $user->notify(new BookingReminder($booking));
            }

        })->dailyAt('17:00');

        // remind users for ending trials
        $schedule->call(function () {
            // get user where the trial ends in 3 days
            $users_on_trial = User::whereDay('trial_ends_at', '=', now()->addDays(3))->get();

            foreach ($users_on_trial as $user) {
                $user->notify(new UserTrialEnding());
            }

        })->dailyAt('08:00');


        // generate a snapshot for horizon in production
        if (env('APP_ENV') == 'production') {
            $schedule->command('horizon:snapshot')->everyFiveMinutes();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
