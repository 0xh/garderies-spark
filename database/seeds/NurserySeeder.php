<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Nursery;

class NurserySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $nurseries_real = [
            "Croquelune",
            "Croquesoleil",
            "Les Lucioles",
            "La Toupie",
            "La Chenoille",
            "Le Chap'rond rouge",
            "L'arlequin",
            "Les Moussaillons",
            "Les Funambules",
            "Croquétoile",
            "Les Laurelles",
            "Les Chavannes",
            "Les Lionceaux",
            "Les Frimousses",
            "L'atelier Magique",
            "Perlimpimpim",
            "L'Oasis",
            "Calimero",
            "Canailles",
            "Les Lucioles",
            "Petites couleurs",
            "L'oiseau Lyre",
            "L'Ondine",
            "Plein Soleil",
            "Les Petits Poucets",
            "L'Attique"
        ];

        $nurseries = [
            "Croquemi",
            "Croquemoi",
            "Les Colibris",
            "La Fée",
            "La Chenille",
            "La Princesse",
            "Le rigole eau",
            "Les Marins",
            "Les Casse cou",
            "Croquetis",
            "Les Artistes",
            "Les Pelages",
            "Les Petits chats",
            "Les Chenapands",
            "L'atelier Imaginaire",
            "Perlimpimpim",
            "L'Oasis",
            "Guillaume Tell",
            "Cache-cache",
            "Les Lucioles",
            "L'arc-en-ciel",
            "L'oiseau rare",
            "La marée haute",
            "Brillant Soleil",
            "Les sept nains",
            "Le Grenier"
        ];

        DB::table('nurseries')->insert([
            'name'          => 'Garderie DevWeb',
            'team_id'       => 1,
            'network_id'    => 1
        ]);
    }
}
