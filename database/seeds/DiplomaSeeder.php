<?php

use Illuminate\Database\Seeder;

class DiplomaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('diplomas')->insert(['name' => 'Auxiliaire']);
        DB::table('diplomas')->insert(['name' => 'ASE']);
        DB::table('diplomas')->insert(['name' => 'Educatrice ES']);
    }
}
