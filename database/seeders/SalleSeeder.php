<?php

namespace Database\Seeders;

use App\Models\Salle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Salle::insert([
            'numero'=>101,
            'capacite'=>50,
            'en_service'=>true,
            'type'=>'cours'
        ]);
    }
}
