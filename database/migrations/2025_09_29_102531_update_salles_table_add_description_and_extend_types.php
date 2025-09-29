<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('salles', function (Blueprint $table) {
            // Ajouter le champ description
            $table->text('description')->nullable()->after('type');

            // Modifier le champ numero pour accepter des chaînes (A-101, B-102, etc.)
            $table->string('numero')->change();
        });

        // Mettre à jour les types existants
        DB::statement("ALTER TABLE salles MODIFY COLUMN type ENUM('cours', 'td_tp', 'laboratoire', 'examen', 'reunion', 'conference', 'atelier', 'studio', 'amphi', 'informatique')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salles', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->integer('numero')->change();
        });

        // Revenir aux types originaux
        DB::statement("ALTER TABLE salles MODIFY COLUMN type ENUM('cours','amphi','informatique')");
    }
};
