<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestTeacherPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:teacher-permissions {--teacher= : Email de l\'enseignant à tester}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester les permissions des enseignants dans le panel Filament';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TEST DES PERMISSIONS ENSEIGNANTS ===');
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
        $this->line("Rôle: {$teacher->role}");

        // Récupérer l'enseignant
        $enseignant = \App\Models\Enseignant::where('user_id', $teacher->id)->first();

        if (!$enseignant) {
            $this->error('Aucun profil enseignant trouvé pour cet utilisateur.');
            return;
        }

        // Récupérer les matières assignées
        $matieres = \App\Models\Matiere::where('enseignant_id', $enseignant->id)
            ->with(['unite', 'semestre'])
            ->get();

        $this->line('');
        $this->info("📚 Matières assignées ({$matieres->count()}):");

        if ($matieres->isEmpty()) {
            $this->warn('Aucune matière assignée à cet enseignant.');
        } else {
            foreach ($matieres as $matiere) {
                $this->line("  - {$matiere->unite->nom} (Semestre: {$matiere->semestre->slug})");
            }
        }

        // Récupérer les fichiers de ses matières
        $fichiers = \App\Models\Fichier::whereHas('matiere', function($q) use ($enseignant) {
            $q->where('enseignant_id', $enseignant->id);
        })->get();

        $this->line('');
        $this->info("📁 Fichiers accessibles ({$fichiers->count()}):");

        if ($fichiers->isEmpty()) {
            $this->warn('Aucun fichier trouvé pour les matières de cet enseignant.');
        } else {
            foreach ($fichiers as $fichier) {
                $this->line("  - {$fichier->nom} ({$fichier->categorie})");
            }
        }

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
            $enseignant = \App\Models\Enseignant::where('user_id', $teacher->id)->first();

            if (!$enseignant) {
                $this->error("❌ {$teacher->name} - Aucun profil enseignant");
                continue;
            }

            $matieresCount = \App\Models\Matiere::where('enseignant_id', $enseignant->id)->count();
            $fichiersCount = \App\Models\Fichier::whereHas('matiere', function($q) use ($enseignant) {
                $q->where('enseignant_id', $enseignant->id);
            })->count();

            $this->line("👤 {$teacher->name} ({$teacher->email})");
            $this->line("   📚 Matières: {$matieresCount}");
            $this->line("   📁 Fichiers: {$fichiersCount}");
            $this->line('');
        }

        $this->info('✅ Test terminé pour tous les enseignants');
    }
}
