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
        Schema::create('administrateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('niveau', ['super_admin', 'admin', 'moderateur'])->default('admin');
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->string('departement')->nullable(); // Ex: "Informatique", "Gestion", etc.
            $table->text('permissions')->nullable(); // JSON pour permissions spécifiques
            $table->timestamp('derniere_connexion')->nullable();
            $table->string('telephone_bureau')->nullable();
            $table->text('notes')->nullable(); // Notes internes sur l'admin
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrateurs');
    }
};
