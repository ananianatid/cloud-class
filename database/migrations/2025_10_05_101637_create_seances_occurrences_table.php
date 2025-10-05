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
        //Organisation
        Schema::create('seances_occurrences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('seances_template')->onDelete('cascade');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->foreignId('salle_id')->constrained('salles')->onDelete('cascade');
            $table->foreignId('professeur_id')->constrained('professeurs')->onDelete('cascade');
            $table->enum('statut', ['planifiée', 'en_cours', 'terminée', 'annulée'])->default('planifiée');
            $table->text('raison_annulation')->nullable();
            $table->text('notes_seance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seances_occurrences');
    }
};
