<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create network admin
        DB::table('users')->insert([
            'id'            => 1,
            'name'          => 'Gestionnaire',
            'email'         => 'admin@garderie.ch',
            'phone'         => '+41211234567',
            'password'      => bcrypt('123456'),
            'created_at'    => \Carbon\Carbon::now()
        ]);

        factory(App\User::class, 100)->create();
    }
}
