<?php

namespace App\Console\Commands;

use App\Models\Promotion;
use App\Models\Diplome;
use App\Models\Filiere;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPromotionsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotions:fix {--dry-run : Afficher les modifications sans les appliquer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger et nettoyer les données des promotions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('🔧 Correction des données des promotions...');

        if ($dryRun) {
            $this->warn('Mode simulation activé - aucune modification ne sera appliquée');
        }

        // 1. Corriger les noms de promotions
        $this->fixPromotionNames($dryRun);

        // 2. Valider les années
        $this->validateYears($dryRun);

        // 3. Supprimer les doublons
        $this->removeDuplicates($dryRun);

        // 4. Mettre à jour les statuts
        $this->updateStatuts($dryRun);

        // 5. Afficher les statistiques
        $this->showStatistics();

        $this->info('✅ Correction terminée !');
    }

    private function fixPromotionNames(bool $dryRun): void
    {
        $this->info('📝 Correction des noms de promotions...');

        $promotions = Promotion::with(['diplome', 'filiere'])->get();
        $fixed = 0;

        foreach ($promotions as $promotion) {
            $newName = $this->generateCorrectName($promotion);

            if ($newName !== $promotion->nom) {
                $this->line("  - {$promotion->nom} → {$newName}");

                if (!$dryRun) {
                    $promotion->update(['nom' => $newName]);
                }
                $fixed++;
            }
        }

        $this->info("  ✅ {$fixed} noms corrigés");
    }

    private function validateYears(bool $dryRun): void
    {
        $this->info('📅 Validation des années...');

        $invalid = Promotion::where('annee_fin', '<', 'annee_debut')->get();

        if ($invalid->count() > 0) {
            $this->error("  ❌ {$invalid->count()} promotions avec des années invalides trouvées :");

            foreach ($invalid as $promotion) {
                $this->line("    - {$promotion->nom} : {$promotion->annee_debut}-{$promotion->annee_fin}");

                if (!$dryRun) {
                    // Corriger en inversant les années
                    $promotion->update([
                        'annee_debut' => $promotion->annee_fin,
                        'annee_fin' => $promotion->annee_debut
                    ]);
                }
            }
        } else {
            $this->info("  ✅ Toutes les années sont valides");
        }
    }

    private function removeDuplicates(bool $dryRun): void
    {
        $this->info('🔄 Suppression des doublons...');

        $duplicates = DB::table('promotions')
            ->select('diplome_id', 'filiere_id', 'annee_debut', 'annee_fin', DB::raw('COUNT(*) as count'))
            ->groupBy('diplome_id', 'filiere_id', 'annee_debut', 'annee_fin')
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->count() > 0) {
            $this->warn("  ⚠️  {$duplicates->count()} groupes de doublons trouvés");

            foreach ($duplicates as $duplicate) {
                $promotions = Promotion::where('diplome_id', $duplicate->diplome_id)
                    ->where('filiere_id', $duplicate->filiere_id)
                    ->where('annee_debut', $duplicate->annee_debut)
                    ->where('annee_fin', $duplicate->annee_fin)
                    ->orderBy('created_at')
                    ->get();

                // Garder le premier, supprimer les autres
                $toDelete = $promotions->skip(1);

                foreach ($toDelete as $promotion) {
                    $this->line("    - Suppression : {$promotion->nom}");

                    if (!$dryRun) {
                        $promotion->delete();
                    }
                }
            }
        } else {
            $this->info("  ✅ Aucun doublon trouvé");
        }
    }

    private function updateStatuts(bool $dryRun): void
    {
        $this->info('🔄 Mise à jour des statuts...');

        if (!$dryRun) {
            $updated = Promotion::updateAllStatuts();
            $this->info("  ✅ {$updated} statuts mis à jour");
        } else {
            $this->info("  📋 Simulation : Mise à jour des statuts selon les années");
        }
    }

    private function generateCorrectName(Promotion $promotion): string
    {
        $diplome = $promotion->diplome;
        $filiere = $promotion->filiere;

        if (!$diplome || !$filiere) {
            return $promotion->nom;
        }

        $diplomeCode = strtoupper(substr($diplome->nom, 0, 3));
        $filiereCode = strtoupper($filiere->code);
        $start = substr((string)$promotion->annee_debut, -2);
        $end = substr((string)$promotion->annee_fin, -2);

        return sprintf('%s-%s-%s-%s', $diplomeCode, $filiereCode, $start, $end);
    }

    private function showStatistics(): void
    {
        $this->info('📊 Statistiques des promotions :');

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
