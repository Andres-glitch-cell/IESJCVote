<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vote_recordeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->string('vote_hash')->unique(); // Requisito del Punto 7 (Resguardo)
            $table->timestamps();

            // Garantiza el Punto 2: Un solo voto por usuario y encuesta
            $table->unique(['user_id', 'survey_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_recordeds');
    }
};
