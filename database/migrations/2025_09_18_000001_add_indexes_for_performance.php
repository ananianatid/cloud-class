<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('semestres')) {
            Schema::table('semestres', function (Blueprint $table) {
                $table->index(['promotion_id', 'date_debut']);
                $table->index('promotion_id');
            });
        }

        if (Schema::hasTable('emplois_du_temps')) {
            Schema::table('emplois_du_temps', function (Blueprint $table) {
                $table->index(['semestre_id', 'debut']);
                $table->index(['semestre_id', 'actif']);
                $table->index('actif');
            });
        }

        if (Schema::hasTable('cours')) {
            Schema::table('cours', function (Blueprint $table) {
                $table->index(['emploi_du_temps_id', 'jour', 'debut']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('semestres')) {
            Schema::table('semestres', function (Blueprint $table) {
                $table->dropIndex('semestres_promotion_id_date_debut_index');
                $table->dropIndex('semestres_promotion_id_index');
            });
        }

        if (Schema::hasTable('emplois_du_temps')) {
            Schema::table('emplois_du_temps', function (Blueprint $table) {
                $table->dropIndex('emplois_du_temps_semestre_id_debut_index');
                $table->dropIndex('emplois_du_temps_semestre_id_actif_index');
                $table->dropIndex('emplois_du_temps_actif_index');
            });
        }

        if (Schema::hasTable('cours')) {
            Schema::table('cours', function (Blueprint $table) {
                $table->dropIndex('cours_emploi_du_temps_id_jour_debut_index');
            });
        }
    }
};

