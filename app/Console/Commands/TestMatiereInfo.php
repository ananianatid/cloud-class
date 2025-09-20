<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMatiereInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:matiere-info {--teacher= : Email de l\'enseignant à tester}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester l\'affichage des informations de matière pour les enseignants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TEST DES INFORMATIONS DE MATIÈRE ===');
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

        // Récupérer l'enseignant
        $enseignant = \App\Models\Enseignant::where('user_id', $teacher->id)->first();

        if (!$enseignant) {
            $this->error('Aucun profil enseignant trouvé pour cet utilisateur.');
            return;
        }

        // Récupérer les matières avec toutes les informations
        $matieres = \App\Models\Matiere::where('enseignant_id', $enseignant->id)
            ->with(['unite', 'semestre.promotion'])
            ->get();

        $this->info("📚 Matières assignées ({$matieres->count()}):");
        $this->line('');

        if ($matieres->isEmpty()) {
            $this->warn('Aucune matière assignée à cet enseignant.');
        } else {
            foreach ($matieres as $matiere) {
                $this->line("🔹 Matière ID: {$matiere->id}");
                $this->line("   📚 Unité: " . ($matiere->unite->nom ?? 'Unité inconnue'));
                $this->line("   🎓 Promotion: " . ($matiere->semestre->promotion->nom ?? 'Promotion inconnue'));
                $this->line("   📅 Semestre: " . ($matiere->semestre->slug ?? 'Semestre inconnu'));
                $this->line("   📝 Label complet: " . $this->generateMatiereLabel($matiere));
                $this->line('');
            }
        }

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

            $matieres = \App\Models\Matiere::where('enseignant_id', $enseignant->id)
                ->with(['unite', 'semestre.promotion'])
                ->get();

            $this->line("👤 {$teacher->name} ({$teacher->email})");
            $this->line("   📚 Matières: {$matieres->count()}");

            if ($matieres->isNotEmpty()) {
                $this->line("   📋 Détails:");
                foreach ($matieres as $matiere) {
                    $label = $this->generateMatiereLabel($matiere);
                    $this->line("      - {$label}");
                }
            }
            $this->line('');
        }

        $this->info('✅ Test terminé pour tous les enseignants');
    }

    private function generateMatiereLabel($matiere)
    {
        $uniteNom = $matiere->unite->nom ?? 'Unité inconnue';
        $promotionNom = $matiere->semestre->promotion->nom ?? 'Promotion inconnue';
        $semestreSlug = $matiere->semestre->slug ?? 'Semestre inconnu';

        return "{$uniteNom} - {$promotionNom} - {$semestreSlug}";
    }
}
