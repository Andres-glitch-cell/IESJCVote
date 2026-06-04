<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título de la votación (ej: Consell Escolar 2026)
            $table->text('description')->nullable();
            $table->string('type');  // Tipo de votación (single_option, multi_option, etc.)
            $table->boolean('is_real_time_enabled')->default(true);
            $table->boolean('is_anonymous')->default(true);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};
