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
        //Ressources pédagogiques
        Schema::create('fichiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('ajoute_par')->constrained('users')->onDelete('cascade');
            $table->string('chemin');
            $table->string('nom');
            $table->string('nom_original')->nullable();
            $table->enum('categorie', ['cours', 'td', 'tp', 'examen', 'corrige', 'autre']);
            $table->boolean('visible')->default(true);
            $table->string('taille');
            $table->integer('telechargements')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichiers');
    }
};
