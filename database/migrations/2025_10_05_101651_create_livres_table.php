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
        //Bibliothèque
        Schema::create('livres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categorie_livres')->onDelete('cascade');
            $table->string('titre');
            $table->string('isbn')->unique();
            $table->string('chemin_fichier');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livres');
    }
};
