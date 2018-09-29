<?php
use Illuminate\Database\Seeder;
use App\Network;
use Faker\Generator as Faker;

class NetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        DB::table('networks')->insert([
            'name'      => 'Réseau DevWeb',
            'slug'      => 'reseau-devweb',
            'team_id'   => 1,
            'color'     => '#3f51b5',
        ]);
    }
}
