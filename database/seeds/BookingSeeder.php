<?php

use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users      = \App\User::all()->count();
        $nurseries  = \App\Nursery::all()->count();

        for($m = 1; $m <= 12; $m++) {

            for($i = 0; $i < rand(8, 23); $i++) {
                $day = $i;

                $start  = \Carbon\Carbon::create(date('Y'), $m)->addDay($day)->hour(rand(6, 11))->minute(0);
                $end    = \Carbon\Carbon::create(date('Y'), $m)->addDay($day)->hour(rand(12, 18))->minute(0);

                if ($start->isSaturday()) {
                    $start->addDay(2);
                    $end->addDay(2);
                    $i+=2;
                } elseif ($start->isSunday()) {
                    $start->addDay(1);
                    $end->addDay(1);
                    $i++;
                }

                $status = ($start < now()) ? \App\Booking::STATUS_ARCHIVED : \App\Booking::STATUS_APPROVED;

                $user_id        = rand(2, $users);
                $substitute_id  = rand(2, $users);
                $nursery_id     = rand(1, $nurseries);
                $purpose_id     = rand(1, 3);

                DB::table('bookings')->insert([
                    'user_id'           => $user_id,
                    'substitute_id'     => $substitute_id,
                    'nursery_id'        => $nursery_id,
                    'purpose_id'        => $purpose_id,
                    'start'             => $start,
                    'end'               => $end,
                    'status'            => $status,
                    'created_at'        => \Carbon\Carbon::now()
                ]);
            }
        }
    }
}
