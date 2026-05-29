<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            // Vincula de forma segura la opción a su encuesta correspondiente
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->string('option_text'); // Texto de la opción ("Opción A", etc.)
            $table->integer('votes_count')->default(0); // Contador de votos integrado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};