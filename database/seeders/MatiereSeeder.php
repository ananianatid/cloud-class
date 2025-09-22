<?php

namespace Database\Seeders;

use App\Models\Matiere;
use App\Models\UniteEnseignement;
use App\Models\Semestre;
use App\Models\Enseignant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Target semestre: numero 1 (for promotion 1)
        $semestre1 = Semestre::where('numero', 1)->where('promotion_id', 1)->first();
        if (!$semestre1) {
            return;
        }

        $enseignantId = (int) (Enseignant::min('id') ?? 1);

        $unites = UniteEnseignement::orderBy('id')->limit(6)->pluck('id');
        foreach ($unites as $uniteId) {
            Matiere::updateOrCreate(
                [
                    'unite_id' => $uniteId,
                    'semestre_id' => $semestre1->id,
                ],
                [
                    'enseignant_id' => $enseignantId,
                ]
            );
        }
    }
}
