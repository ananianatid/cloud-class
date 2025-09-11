<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\EnrollmentKeySeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'jane doe',
            'email' => 'JaneDoe@gmail.com',
            'password'=>'password',
            'role'=>'etudiant',
            'sexe'=>'F'
        ]);

        $this->call(DiplomeSeeder::class);
        $this->call(FiliereSeeder::class);
        $this->call(PromotionSeeder::class);
        $this->call(EnrollmentKeySeeder::class);
    }
}
