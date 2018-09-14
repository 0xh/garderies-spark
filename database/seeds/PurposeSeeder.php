<?php

use Illuminate\Database\Seeder;

class PurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('purposes')->insert(['name' => 'Maladie']);
        DB::table('purposes')->insert(['name' => 'Vacances']);
        DB::table('purposes')->insert(['name' => 'Congé maternité']);
        DB::table('purposes')->insert(['name' => 'Service militaire / civil']);
    }
}
