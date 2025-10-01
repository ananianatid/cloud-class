<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Les contraintes de validation seront gérées au niveau de l'application
        // via les validations Laravel dans le modèle et les formulaires
        // L'unicité est déjà gérée dans la migration principale
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rien à faire car on n'a rien ajouté
    }
};
