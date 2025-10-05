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
        Schema::create('cle_inscription', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->foreignId('used_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->onDelete('cascade');
            $table->foreignId('revoked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->dateTime('used_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('revoked_at')->nullable();
            $table->string('revoked_reason')->nullable();
            $table->enum('status', ['actif', 'utilisé', 'expiré', 'révoqué'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cle_inscription');
    }
};
