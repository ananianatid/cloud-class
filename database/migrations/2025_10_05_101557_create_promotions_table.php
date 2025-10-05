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
        //Gestion académique
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diplome_id')->constrained('diplomes')->onDelete('cascade');
            $table->foreignId('filiere_id')->constrained('filieres')->onDelete('cascade');
            $table->string('nom');
            $table->year('debut');
            $table->year('fin');
            $table->string('description')->nullable();
            $table->enum('statut', ['actif', 'archive'])->default('actif');
            $table->foreignId('responsable_id')->nullable()->constrained('responsables')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
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
