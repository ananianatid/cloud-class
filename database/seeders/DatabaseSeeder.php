<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Promotion;
use Database\Seeders\EnrollmentKeySeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $jane = User::factory()->create([
            'name' => 'jane doe',
            'email' => 'JaneDoe@gmail.com',
            'password'=>'password',
            'role'=>'etudiant',
            'sexe'=>'F'
        ]);

        // Seeders de base
        $this->call(DiplomeSeeder::class);
        $this->call(FiliereSeeder::class);
        $this->call(UniteEnseignementSeeder::class);

        // Promotion et semestres
        $this->call(PromotionSeeder::class);
        $this->call(SemestreSeeder::class);

        // Utilisateurs
        $this->call(EnseignantSeeder::class);
        $this->call(AdministrateurSeeder::class);
        $this->call(EtudiantSeeder::class);

        // Matières et emplois du temps
        $this->call(MatiereSeeder::class);
        $this->call(EmploiDuTempsSeeder::class);
        $this->call(CoursSeeder::class);

        // Clés d'inscription
        $this->call(EnrollmentKeySeeder::class);

        // Événements du calendrier
        $this->call(EvenementSeeder::class);
    }
}
