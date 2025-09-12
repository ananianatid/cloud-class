<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    for ($i = 1; $i <= 6; $i++) {
        \App\Models\Semestre::create([
            'numero' => $i,
            'slug' => 'semestre-' . $i,
            'promotion_id' => 1,
        ]);
    }
    }
}
