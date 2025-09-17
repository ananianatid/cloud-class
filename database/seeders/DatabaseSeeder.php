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

        $this->call(DiplomeSeeder::class);
        $this->call(FiliereSeeder::class);
        $this->call(PromotionSeeder::class);
        $this->call(EnrollmentKeySeeder::class);
        $this->call(EnseignantSeeder::class);
        $this->call(UniteEnseignementSeeder::class);
        $this->call(SemestreSeeder::class);
        $this->call(MatiereSeeder::class);
        $this->call(FichierSeeder::class);
        $this->call(EmploiDuTempsSeeder::class);
        $this->call(CoursSeeder::class);

        // Default student user (after promotions exist)
        $john = User::firstOrCreate(
            ['email' => 'johndoe@gmail.com'],
            [
                'name' => 'John Doe',
                'password' => 'password',
                'role' => 'etudiant',
                'sexe' => 'M',
            ]
        );

        // Attach Etudiant profile to default user with first promotion if exists
        $promotion = Promotion::first();
        if ($promotion) {
            Etudiant::firstOrCreate(
                ['user_id' => $john->id],
                [
                    'promotion_id' => $promotion->id,
                    'matricule' => 'MAT-' . str_pad((string) $john->id, 5, '0', STR_PAD_LEFT),
                    'naissance' => Carbon::parse('2000-01-01')->toDateString(),
                    'statut' => 'actif',
                ]
            );
        }


    }
}
