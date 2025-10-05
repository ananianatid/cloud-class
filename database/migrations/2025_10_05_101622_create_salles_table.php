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
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->integer('capacite');
            $table->boolean('en_service');
            $table->enum('type', ['amphitheatre', 'salle_cours', 'laboratoire', 'salle_informatique'])->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};
