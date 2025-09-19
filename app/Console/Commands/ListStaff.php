<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staff:list {--role= : Filtrer par rôle (enseignant, administrateur)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lister les enseignants et administrateurs avec leurs informations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== PERSONNEL CLOUD CLASS ===');
        $this->line('Mot de passe pour tous: $helsinki');
        $this->line('');

        $role = $this->option('role');

        if ($role) {
            $this->listByRole($role);
        } else {
            $this->listAll();
        }
    }

    private function listByRole($role)
    {
        if (!in_array($role, ['enseignant', 'administrateur'])) {
            $this->error('Rôle invalide. Utilisez: enseignant ou administrateur');
            return;
        }

        $users = \App\Models\User::where('role', $role)->get();

        if ($users->isEmpty()) {
            $this->warn("Aucun {$role} trouvé.");
            return;
        }

        $this->line("=== {$role}s ===");
        $this->line('');

        foreach ($users as $user) {
            $this->line("👤 {$user->name}");
            $this->line("   📧 {$user->email}");
            $this->line("   🚹🚺 {$user->sexe}");

            if ($role === 'enseignant') {
                $enseignant = \App\Models\Enseignant::where('user_id', $user->id)->first();
                if ($enseignant) {
                    $this->line("   📚 Statut: {$enseignant->statut}");
                    $this->line("   📝 Bio: " . substr($enseignant->bio, 0, 100) . '...');
                }
            }

            $this->line('   🔑 Mot de passe: $helsinki');
            $this->line('');
        }

        $this->info("Total {$role}s: " . $users->count());
    }

    private function listAll()
    {
        // Enseignants
        $enseignants = \App\Models\User::where('role', 'enseignant')->get();
        $this->line('=== 👨‍🏫 ENSEIGNANTS ===');
        $this->line('');

        foreach ($enseignants as $user) {
            $enseignant = \App\Models\Enseignant::where('user_id', $user->id)->first();
            $this->line("👤 {$user->name} ({$user->email})");
            if ($enseignant) {
                $this->line("   📚 Statut: {$enseignant->statut}");
            }
            $this->line('');
        }

        // Administrateurs
        $administrateurs = \App\Models\User::where('role', 'administrateur')->get();
        $this->line('=== 👨‍💼 ADMINISTRATEURS ===');
        $this->line('');

        foreach ($administrateurs as $user) {
            $this->line("👤 {$user->name} ({$user->email})");
            $this->line('');
        }

        $this->info("Résumé:");
        $this->line("- Enseignants: " . $enseignants->count());
        $this->line("- Administrateurs: " . $administrateurs->count());
        $this->line("- Total: " . ($enseignants->count() + $administrateurs->count()));
    }
}
