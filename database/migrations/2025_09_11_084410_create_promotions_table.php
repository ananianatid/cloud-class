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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('diplome_id')->constrained('diplomes')->cascadeOnDelete();
            $table->foreignId('filiere_id')->constrained('filieres')->cascadeOnDelete();
            $table->year('annee_debut');
            $table->year('annee_fin');
            $table->string('description')->nullable();
            $table->enum('statut', ['actif', 'archive'])
                  ->default('actif')
                  ->comment('Statut de la promotion: actif si la date actuelle est comprise entre annee_debut et annee_fin, sinon archive');
            $table->timestamps();

            // Contraintes pour éviter les doublons
            $table->unique(['diplome_id', 'filiere_id', 'annee_debut', 'annee_fin'], 'unique_promotion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
