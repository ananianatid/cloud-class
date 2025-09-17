<?php

namespace Database\Seeders;

use App\Models\Cours;
use App\Models\EmploiDuTemps;
use App\Models\Matiere;
use App\Models\Salle;
use App\Models\Semestre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find principal timetable for semester 5 (promotion 1)
        $semestre5 = Semestre::where('numero', 5)->where('promotion_id', 1)->first();
        if (!$semestre5) {
            return;
        }

        $edt = EmploiDuTemps::where('semestre_id', $semestre5->id)
            ->where('categorie', 'principal')
            ->first();
        if (!$edt) {
            return;
        }

        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];

        // Collect available matieres and salles
        $matieres = Matiere::where('semestre_id', $semestre5->id)->pluck('id')->all();
        if (empty($matieres)) {
            return;
        }
        // Ensure there is at least one salle
        $salle = Salle::first();
        if (!$salle) {
            $salle = Salle::create([
                'numero' => 101,
                'capacite' => 40,
                'en_service' => true,
                'type' => 'cours',
            ]);
        }
        $salleId = (int) $salle->id;

        // Define time slots from 07:00 to 18:00 with breaks and gaps
        $slots = [
            ['07:00', '09:00'],
            ['09:15', '11:15'], // break 15min
            ['12:00', '13:30'], // lunch gap before
            ['14:00', '16:00'],
            ['16:15', '17:45'], // small break
        ];

        foreach ($jours as $jour) {
            $matiereIndex = 0;
            foreach ($slots as [$debut, $fin]) {
                $matiereId = $matieres[$matiereIndex % count($matieres)];
                $matiereIndex++;

                Cours::updateOrCreate(
                    [
                        'emploi_du_temps_id' => $edt->id,
                        'jour' => $jour,
                        'debut' => $debut,
                        'fin' => $fin,
                    ],
                    [
                        'matiere_id' => $matiereId,
                        'salle_id' => $salleId,
                        'type' => 'cours',
                    ]
                );
            }
        }
    }
}
