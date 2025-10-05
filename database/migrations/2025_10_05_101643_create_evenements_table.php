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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->string('titre');
            $table->text('corps');
            $table->date('date');
            $table->time('heure')->nullable();
            $table->string('couleur', 7)->default('#3B82F6');
            $table->string('type');
            $table->enum('public_cible', ['Étudiants', 'Professeurs', 'Tous'])->default('Tous');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
