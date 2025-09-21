<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestTeacherDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:teacher-dashboard {--teacher= : Email de l\'enseignant à tester}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester les données du dashboard des enseignants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TEST DU DASHBOARD ENSEIGNANTS ===');
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

        $this->line("Test du dashboard pour: {$teacher->name} ({$teacher->email})");
        $this->line('');

        $enseignant = \App\Models\Enseignant::where('user_id', $teacher->id)->first();

        if (!$enseignant) {
            $this->error('Aucun profil enseignant trouvé pour cet utilisateur.');
            return;
        }

        $this->displayTeacherStats($enseignant);
        $this->displayTeacherMatieres($enseignant);
        $this->displayTeacherSchedule($enseignant);

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

        $this->line("Test du dashboard pour {$teachers->count()} enseignants:");
        $this->line('');

        foreach ($teachers as $teacher) {
            $enseignant = \App\Models\Enseignant::where('user_id', $teacher->id)->first();

            if (!$enseignant) {
                $this->error("❌ {$teacher->name} - Aucun profil enseignant");
                continue;
            }

            $this->line("👤 {$teacher->name} ({$teacher->email})");
            $this->displayTeacherStats($enseignant, true);
            $this->line('');
        }

        $this->info('✅ Test terminé pour tous les enseignants');
    }

    private function displayTeacherStats($enseignant, $compact = false)
    {
        // Compter les fichiers uploadés par l'enseignant
        $fichiersCount = \App\Models\Fichier::whereHas('matiere', function($q) use ($enseignant) {
            $q->where('enseignant_id', $enseignant->id);
        })->count();

        // Compter les matières totales assignées à l'enseignant
        $matieresTotalCount = \App\Models\Matiere::where('enseignant_id', $enseignant->id)->count();

        // Compter les matières actives cette année (2025)
        $matieresActivesCount = \App\Models\Matiere::where('enseignant_id', $enseignant->id)
            ->whereHas('semestre', function($q) {
                $q->where('date_debut', '<=', now())
                  ->where('date_fin', '>=', now());
            })->count();

        // Compter les cours dans l'emploi du temps actif
        $coursActifsCount = \App\Models\Cours::whereHas('matiere', function($q) use ($enseignant) {
            $q->where('enseignant_id', $enseignant->id);
        })->whereHas('emploiDuTemps', function($q) {
            $q->where('actif', true);
        })->count();

        if ($compact) {
            $this->line("   📊 Fichiers: {$fichiersCount} | Matières: {$matieresTotalCount} | Actives: {$matieresActivesCount} | Cours: {$coursActifsCount}");
        } else {
            $this->info("📊 Statistiques du Dashboard:");
            $this->line("   📄 Fichiers Uploadés: {$fichiersCount}");
            $this->line("   📚 Matières Totales: {$matieresTotalCount}");
            $this->line("   🎓 Matières Actives: {$matieresActivesCount}");
            $this->line("   📅 Cours Actifs: {$coursActifsCount}");
        }
    }

    private function displayTeacherMatieres($enseignant)
    {
        $matieres = \App\Models\Matiere::where('enseignant_id', $enseignant->id)
            ->with(['unite', 'semestre.promotion'])
            ->get();

        $this->line('');
        $this->info("📚 Matières Assignées ({$matieres->count()}):");

        if ($matieres->isEmpty()) {
            $this->warn('Aucune matière assignée à cet enseignant.');
        } else {
            foreach ($matieres as $matiere) {
                $isActive = $matiere->semestre->date_debut <= now() &&
                           $matiere->semestre->date_fin >= now();
                $status = $isActive ? '✅ Actif' : '❌ Inactif';

                $this->line("   🔹 {$matiere->unite->nom}");
                $this->line("      Promotion: {$matiere->semestre->promotion->nom}");
                $this->line("      Semestre: {$matiere->semestre->slug}");
                $this->line("      Statut: {$status}");
                $this->line('');
            }
        }
    }

    private function displayTeacherSchedule($enseignant)
    {
        $cours = \App\Models\Cours::whereHas('matiere', function($q) use ($enseignant) {
            $q->where('enseignant_id', $enseignant->id);
        })->whereHas('emploiDuTemps', function($q) {
            $q->where('actif', true);
        })->with(['matiere.unite', 'matiere.semestre.promotion', 'emploiDuTemps', 'salle'])
        ->orderBy('jour')
        ->get();

        $this->line('');
        $this->info("📅 Emploi du Temps ({$cours->count()} cours):");

        if ($cours->isEmpty()) {
            $this->warn('Aucun cours trouvé dans l\'emploi du temps actif.');
        } else {
            foreach ($cours as $coursItem) {
                $this->line("   📝 {$coursItem->matiere->unite->nom}");
                $this->line("      Jour: {$coursItem->jour} | Heure: {$coursItem->debut}-{$coursItem->fin}");
                $this->line("      Type: {$coursItem->type} | Salle: {$coursItem->salle->numero}");
                $this->line("      Promotion: {$coursItem->matiere->semestre->promotion->nom}");
                $this->line('');
            }
        }
    }
}
