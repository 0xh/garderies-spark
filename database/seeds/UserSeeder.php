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
            'name'          => 'Simon Rapin',
            'email'         => 'simon@devweb.ch',
            'phone'         => '+41211234567',
            'password'      => bcrypt('123456'),
            'created_at'    => \Carbon\Carbon::now(),
        ]);
        DB::table('users')->insert([
            'name'          => 'Henrique Barbosa',
            'email'         => 'henrique@devweb.ch',
            'phone'         => '+41211234567',
            'password'      => bcrypt('123456'),
            'created_at'    => \Carbon\Carbon::now(),
        ]);
    }
}
