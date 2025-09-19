<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestStudentAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:student-access {--student= : Email de l\'étudiant à tester}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester l\'accès des étudiants au panel Filament';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TEST D\'ACCÈS AU PANEL FILAMENT ===');
        $this->line('');

        $studentEmail = $this->option('student');

        if ($studentEmail) {
            $this->testSpecificStudent($studentEmail);
        } else {
            $this->testAllStudents();
        }
    }

    private function testSpecificStudent($email)
    {
        $student = \App\Models\User::where('email', $email)->first();

        if (!$student) {
            $this->error("Aucun étudiant trouvé avec l'email: {$email}");
            return;
        }

        $this->line("Test de l'étudiant: {$student->name} ({$student->email})");
        $this->line("Rôle: {$student->role}");

        if ($student->role === 'etudiant') {
            $this->error('❌ ACCÈS BLOQUÉ - L\'étudiant ne peut pas accéder au panel Filament');
        } else {
            $this->info('✅ ACCÈS AUTORISÉ - L\'utilisateur peut accéder au panel Filament');
        }
    }

    private function testAllStudents()
    {
        $students = \App\Models\User::where('email', 'like', '%@test.com')->get();

        if ($students->isEmpty()) {
            $this->warn('Aucun étudiant de test trouvé.');
            return;
        }

        $this->line("Test de {$students->count()} étudiants de test:");
        $this->line('');

        $blocked = 0;
        $allowed = 0;

        foreach ($students as $student) {
            if ($student->role === 'etudiant') {
                $this->error("❌ {$student->name} ({$student->email}) - BLOQUÉ");
                $blocked++;
            } else {
                $this->info("✅ {$student->name} ({$student->email}) - AUTORISÉ");
                $allowed++;
            }
        }

        $this->line('');
        $this->info("Résumé:");
        $this->line("- Étudiants bloqués: {$blocked}");
        $this->line("- Utilisateurs autorisés: {$allowed}");

        if ($blocked > 0) {
            $this->info('✅ La restriction d\'accès fonctionne correctement!');
        } else {
            $this->warn('⚠️  Aucun étudiant bloqué trouvé. Vérifiez la configuration.');
        }
    }
}
