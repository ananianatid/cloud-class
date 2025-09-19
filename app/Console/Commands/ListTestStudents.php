<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListTestStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:test-list {--promotion= : Filtrer par promotion}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lister tous les étudiants de test avec leurs informations de connexion';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== ÉTUDIANTS DE TEST ===');
        $this->line('Mot de passe pour tous les étudiants: $helsinki');
        $this->line('');

        $query = \App\Models\Etudiant::with(['user', 'promotion.filiere'])
            ->whereHas('user', function($q) {
                $q->where('email', 'like', '%@test.com');
            });

        if ($promotion = $this->option('promotion')) {
            $query->whereHas('promotion', function($q) use ($promotion) {
                $q->where('nom', 'like', '%' . $promotion . '%');
            });
        }

        $students = $query->orderBy('promotion_id')->get();

        if ($students->isEmpty()) {
            $this->warn('Aucun étudiant de test trouvé.');
            return;
        }

        $this->table(
            ['Nom', 'Email', 'Matricule', 'Promotion', 'Filière'],
            $students->map(function($student) {
                return [
                    $student->user->name,
                    $student->user->email,
                    $student->matricule,
                    $student->promotion->nom,
                    $student->promotion->filiere->nom
                ];
            })
        );

        $this->line('');
        $this->info('Total: ' . $students->count() . ' étudiants de test');
    }
}
