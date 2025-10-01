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
        Schema::table('promotions', function (Blueprint $table) {
            $table->enum('statut', ['actif', 'archive'])
                  ->default('actif')
                  ->after('description')
                  ->comment('Statut de la promotion: actif si la date actuelle est comprise entre annee_debut et annee_fin, sinon archive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
};
