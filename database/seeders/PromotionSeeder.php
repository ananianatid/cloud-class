<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;
class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::insert([
            [
                'nom' => 'Licence-GL-2023-2026',
                'diplome_id' => 2,
                'filiere_id' => 3,
                'annee_debut' => 2023,
                'annee_fin' => 2026,
                'description' => 'Genie logiciel licence de 2023 a 2026',
            ],
        ]);
    }
}
