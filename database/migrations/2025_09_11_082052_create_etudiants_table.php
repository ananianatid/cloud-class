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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user')->onDelete('null');
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('null');
            $table->string('matricule')->nullable()->unique();
            $table->date('naissance');
            // $table->date('inscripton');
            $table->date('graduatin')->nullable();
            $table->string('parent')->nullable();
            $table->string('telephone_parent')->nullable();
            $table->enum('statut',['actif','diplome','suspendu','abandon']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
