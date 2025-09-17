<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Semestre;
use App\Models\EmploiDuTemps;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmploiDuTempsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['principal', 'examen', 'devoir', 'mission', 'autre'];

        // Define a default date window for timetables
        $startDate = Carbon::now()->startOfMonth()->toDateString();
        $endDate = Carbon::now()->endOfMonth()->toDateString();

        // For each existing semestre, create one timetable per category
        Semestre::with('promotion')->get()->each(function (Semestre $semestre) use ($categories, $startDate, $endDate) {
            foreach ($categories as $categorie) {
                $promotionName = optional($semestre->promotion)->nom ?? 'promotion';
                $nom = Str::slug($promotionName . '-' . $semestre->slug . '-' . $categorie);

                $isActive = $categorie === 'principal' && (int)($semestre->numero ?? 0) === 5;

                EmploiDuTemps::updateOrCreate(
                    ['nom' => $nom],
                    [
                        'semestre_id' => $semestre->id,
                        'categorie' => $categorie,
                        'actif' => $isActive,
                        'debut' => $startDate,
                        'fin' => $endDate,
                        'descrpition' => null,
                    ]
                );
            }
        });
    }
}
