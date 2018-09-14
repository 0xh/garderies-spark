<?php

use Illuminate\Database\Seeder;

class WorkgroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('workgroups')->insert(['name' => 'Bébés']);
        DB::table('workgroups')->insert(['name' => 'Trotteurs']);
        DB::table('workgroups')->insert(['name' => 'Moyens / Grands']);
        DB::table('workgroups')->insert(['name' => 'UAPE / APEMS']);
    }
}
