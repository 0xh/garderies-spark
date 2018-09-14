<?php

use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::all()->count();

        for($user_id = 2; $user_id <= $users; $user_id++) {

            for ($m = 1; $m <= 12; $m++) {
                for ($i = 1; $i <= 2; $i++) {
                    $day    = rand(1,25);
                    $month  = $m;
                    $minute = [0, 30];

                    $start = \Carbon\Carbon::create()
                        ->year(2018)
                        ->month($month)
                        ->addDay($day)
                        ->hour(rand(6, 11))
                        ->minute($minute[rand(0,1)])
                        ->second(0);
                    $end = \Carbon\Carbon::create()
                        ->year(2018)
                        ->month($month)
                        ->addDay($day)
                        ->hour(rand(12, 18))
                        ->minute($minute[rand(0,1)])
                        ->second(0);

                    if ($start->isSaturday()) {
                        $start->addDay(2);
                        $end->addDay(2);
                        $i += 2;
                    } elseif ($start->isSunday()) {
                        $start->addDay(1);
                        $end->addDay(1);
                        $i++;
                    }

                    DB::table('availabilities')->insert([
                        'user_id'       => $user_id,
                        'start'         => $start,
                        'end'           => $end,
                        'created_at'    => \Carbon\Carbon::now()
                    ]);
                }
            }

        }
    }
}
