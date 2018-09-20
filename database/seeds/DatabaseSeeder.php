<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        //$this->call(NurserySeeder::class);
        $this->call(DiplomaSeeder::class);
        $this->call(WorkgroupSeeder::class);
        //$this->call(UserSeeder::class);
        //$this->call(AvailabilitySeeder::class);
        //$this->call(BookingSeeder::class);
        //$this->call(NetworkSeeder::class);
        $this->call(PurposeSeeder::class);
        $this->call(TeamSeeder::class);
    }
}
