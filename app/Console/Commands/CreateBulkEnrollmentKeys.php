<?php

namespace App\Console\Commands;

use App\Models\EnrollmentKey;
use App\Models\Promotion;
use Illuminate\Console\Command;

class CreateBulkEnrollmentKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollment:create-bulk
                            {promotion : ID ou nom de la promotion}
                            {quantity : Nombre de tokens à créer}
                            {--expires= : Date d\'expiration (format: Y-m-d H:i:s)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crée plusieurs tokens d\'inscription pour une promotion donnée';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $promotionIdentifier = $this->argument('promotion');
        $quantity = (int) $this->argument('quantity');
        $expiresAt = $this->option('expires');

        // Rechercher la promotion
        $promotion = is_numeric($promotionIdentifier)
            ? Promotion::find($promotionIdentifier)
            : Promotion::where('nom', 'like', '%' . $promotionIdentifier . '%')->first();

        if (!$promotion) {
            $this->error("Promotion non trouvée : {$promotionIdentifier}");
            return 1;
        }

        // Validation de la quantité
        if ($quantity < 1 || $quantity > 100) {
            $this->error('La quantité doit être entre 1 et 100');
            return 1;
        }

        // Conversion de la date d'expiration
        $expiresAtDate = null;
        if ($expiresAt) {
            try {
                $expiresAtDate = \Carbon\Carbon::parse($expiresAt);
            } catch (\Exception $e) {
                $this->error("Format de date invalide : {$expiresAt}");
                return 1;
            }
        }

        // Confirmation
        $this->info("Promotion sélectionnée : {$promotion->nom}");
        $this->info("Quantité de tokens : {$quantity}");
        if ($expiresAtDate) {
            $this->info("Date d'expiration : {$expiresAtDate->format('d/m/Y H:i')}");
        } else {
            $this->info("Date d'expiration : Aucune");
        }

        if (!$this->confirm('Voulez-vous continuer ?')) {
            $this->info('Opération annulée');
            return 0;
        }

        // Création des tokens
        $this->info('Création des tokens en cours...');

        $tokens = EnrollmentKey::createBulkForPromotion(
            $promotion->id,
            $quantity,
            $expiresAtDate
        );

        $this->info("✅ {$quantity} tokens créés avec succès pour la promotion '{$promotion->nom}'");

        // Affichage des tokens créés
        $this->table(
            ['Token', 'Promotion', 'Expiration'],
            collect($tokens)->map(function ($token) {
                return [
                    $token->key,
                    $token->promotion->nom,
                    $token->expires_at ? $token->expires_at->format('d/m/Y H:i') : 'Jamais'
                ];
            })
        );

        return 0;
    }
}
