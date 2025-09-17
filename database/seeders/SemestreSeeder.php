<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example: each semester lasts 4 months starting from January of current year
        $yearStart = Carbon::now()->startOfYear();
        for ($i = 1; $i <= 6; $i++) {
            $start = (clone $yearStart)->addMonths(($i - 1) * 2); // adjust as needed
            $end = (clone $start)->addMonths(2)->subDay();

            \App\Models\Semestre::create([
                'numero' => $i,
                'slug' => 'semestre-' . $i,
                'promotion_id' => 1,
                'date_debut' => $start->toDateString(),
                'date_fin' => $end->toDateString(),
            ]);
        }
    }
}
