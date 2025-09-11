<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Diplome;
class DiplomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Diplome::create([
            ['nom' => 'BTS','duree'=>2, 'code'=>'B','description' => 'Diplôme de BTS universitaire'],
            ['nom' => 'Licence','duree'=>3,'code'=>'L', 'description' => 'Diplôme de licence universitaire'],
            ['nom' => 'Master','duree'=>5,'code'=>'M', 'description' => 'Diplôme de master universitaire'],
            ['nom' => 'Doctorat','duree'=>8,'code'=>'D', 'description' => 'Diplôme de doctorat universitaire']
        ]);
    }
}
