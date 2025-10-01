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

        // Mettre à jour les types existants - compatible avec MySQL et SQLite
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE salles MODIFY COLUMN type ENUM('cours', 'td_tp', 'laboratoire', 'examen', 'reunion', 'conference', 'atelier', 'studio', 'amphi', 'informatique')");
        } else {
            // Pour SQLite, on ne peut pas modifier l'ENUM directement
            // On laisse le type tel quel pour les tests
        }
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

        // Revenir aux types originaux - compatible avec MySQL et SQLite
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE salles MODIFY COLUMN type ENUM('cours','amphi','informatique')");
        } else {
            // Pour SQLite, on ne peut pas modifier l'ENUM directement
            // On laisse le type tel quel pour les tests
        }
    }
};
