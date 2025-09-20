<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestTeacherNavigation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:teacher-navigation {--teacher= : Email de l\'enseignant à tester}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester que les enseignants ne voient que FichierResource dans la navigation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TEST DE LA NAVIGATION ENSEIGNANTS ===');
        $this->line('');

        $teacherEmail = $this->option('teacher');

        if ($teacherEmail) {
            $this->testSpecificTeacher($teacherEmail);
        } else {
            $this->testAllTeachers();
        }
    }

    private function testSpecificTeacher($email)
    {
        $teacher = \App\Models\User::where('email', $email)->first();

        if (!$teacher) {
            $this->error("Aucun enseignant trouvé avec l'email: {$email}");
            return;
        }

        if ($teacher->role !== 'enseignant') {
            $this->error("L'utilisateur {$email} n'est pas un enseignant (rôle: {$teacher->role})");
            return;
        }

        $this->line("Test de l'enseignant: {$teacher->name} ({$teacher->email})");
        $this->line('');

        // Simuler la connexion de l'enseignant
        \Illuminate\Support\Facades\Auth::login($teacher);

        // Tester toutes les ressources
        $this->testResourceVisibility($teacher);

        $this->line('');
        $this->info('✅ Test terminé pour cet enseignant');
    }

    private function testAllTeachers()
    {
        $teachers = \App\Models\User::where('role', 'enseignant')->get();

        if ($teachers->isEmpty()) {
            $this->warn('Aucun enseignant trouvé.');
            return;
        }

        $this->line("Test de {$teachers->count()} enseignants:");
        $this->line('');

        foreach ($teachers as $teacher) {
            $this->line("👤 {$teacher->name} ({$teacher->email})");

            // Simuler la connexion de l'enseignant
            \Illuminate\Support\Facades\Auth::login($teacher);

            $this->testResourceVisibility($teacher);
            $this->line('');
        }

        $this->info('✅ Test terminé pour tous les enseignants');
    }

    private function testResourceVisibility($teacher)
    {
        $resources = [
            'FichierResource' => \App\Filament\Resources\FichierResource::class,
            'CoursResource' => \App\Filament\Resources\CoursResource::class,
            'EmploiDuTempsResource' => \App\Filament\Resources\EmploiDuTempsResource::class,
            'EnrollmentKeyResource' => \App\Filament\Resources\EnrollmentKeyResource::class,
            'EtudiantResource' => \App\Filament\Resources\EtudiantResource::class,
            'EnseignantResource' => \App\Filament\Resources\EnseignantResource::class,
            'SalleResource' => \App\Filament\Resources\SalleResource::class,
            'DiplomeResource' => \App\Filament\Resources\DiplomeResource::class,
            'UserResource' => \App\Filament\Resources\UserResource::class,
            'UniteEnseignementResource' => \App\Filament\Resources\UniteEnseignementResource::class,
            'FiliereResource' => \App\Filament\Resources\FiliereResource::class,
            'PromotionResource' => \App\Filament\Resources\PromotionResource::class,
            'SemestreResource' => \App\Filament\Resources\SemestreResource::class,
            'MatiereResource' => \App\Filament\Resources\MatiereResource::class,
        ];

        $visibleResources = [];
        $hiddenResources = [];

        foreach ($resources as $name => $resourceClass) {
            try {
                $isVisible = $resourceClass::shouldRegisterNavigation();

                if ($isVisible) {
                    $visibleResources[] = $name;
                } else {
                    $hiddenResources[] = $name;
                }
            } catch (\Exception $e) {
                $this->error("Erreur lors du test de {$name}: " . $e->getMessage());
            }
        }

        $this->line("   ✅ Ressources visibles (" . count($visibleResources) . "):");
        foreach ($visibleResources as $resource) {
            $this->line("      - {$resource}");
        }

        $this->line("   ❌ Ressources masquées (" . count($hiddenResources) . "):");
        foreach ($hiddenResources as $resource) {
            $this->line("      - {$resource}");
        }

        // Vérifier que seul FichierResource est visible
        if (count($visibleResources) === 1 && in_array('FichierResource', $visibleResources)) {
            $this->info("   🎉 SUCCÈS: Seul FichierResource est visible !");
        } else {
            $this->error("   ❌ ÉCHEC: Plus d'une ressource est visible ou FichierResource n'est pas visible !");
        }
    }
}
