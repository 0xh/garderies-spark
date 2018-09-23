<?php

use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teams')->insert(['owner_id' => 1, 'name' => 'Equipe DevWeb']);
        DB::table('team_users')->insert(['team_id' => 1, 'user_id' => 1, 'role' => 'owner']);

        DB::table('team_users')->insert(['team_id' => 1, 'user_id' => 3, 'role' => 'director']);
        DB::table('team_users')->insert(['team_id' => 1, 'user_id' => 4, 'role' => 'substitute']);
        DB::table('team_users')->insert(['team_id' => 1, 'user_id' => 5, 'role' => 'substitute']);
    }
}
