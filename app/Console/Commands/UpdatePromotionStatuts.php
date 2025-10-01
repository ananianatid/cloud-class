<?php

namespace App\Console\Commands;

use App\Models\Promotion;
use Illuminate\Console\Command;

class UpdatePromotionStatuts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotions:update-statuts {--dry-run : Afficher les modifications sans les appliquer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mettre à jour le statut de toutes les promotions (actif/archivé)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('🔄 Mise à jour des statuts des promotions...');
        
        if ($dryRun) {
            $this->warn('Mode simulation activé - aucune modification ne sera appliquée');
        }

        // Afficher les statistiques avant
        $this->showStatistics('Avant');

        if (!$dryRun) {
            $updated = Promotion::updateAllStatuts();
            $this->info("✅ {$updated} statuts mis à jour");
        } else {
            $this->info("📋 Simulation : Mise à jour des statuts selon les années");
        }

        // Afficher les statistiques après
        $this->showStatistics('Après');
        
        $this->info('✅ Mise à jour terminée !');
    }

    private function showStatistics(string $label): void
    {
        $this->info("📊 Statistiques {$label} :");
        
        $total = Promotion::count();
        $active = Promotion::active()->count();
        $archived = Promotion::archived()->count();
        
        $this->table(
            ['Statut', 'Nombre', 'Pourcentage'],
            [
                ['Total', $total, '100%'],
                ['Actives', $active, $total > 0 ? round(($active / $total) * 100, 1) . '%' : '0%'],
                ['Archivées', $archived, $total > 0 ? round(($archived / $total) * 100, 1) . '%' : '0%'],
            ]
        );
    }
}