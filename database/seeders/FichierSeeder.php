<?php

namespace Database\Seeders;

use App\Models\Fichier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FichierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::query()->value('id') ?? 1;

        Fichier::insert([
            [
                'matiere_id' => 1,
                'chemin' => 'cours/intro-algo.pdf',
                'nom' => 'Introduction à l\'algorithmique',
                'categorie' => 'cours',
                'visible' => true,
                'ajoute_par' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matiere_id' => 1,
                'chemin' => 'tdtp/algo-td1.pdf',
                'nom' => 'TD 1 - Bases',
                'categorie' => 'td&tp',
                'visible' => true,
                'ajoute_par' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matiere_id' => 1,
                'chemin' => 'devoirs/devoir-algo1.pdf',
                'nom' => 'Devoir 1',
                'categorie' => 'devoir',
                'visible' => false,
                'ajoute_par' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
