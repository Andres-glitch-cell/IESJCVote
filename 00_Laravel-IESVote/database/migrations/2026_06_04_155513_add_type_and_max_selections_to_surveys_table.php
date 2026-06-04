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
        Schema::table('surveys', function (Blueprint $table) {
            // Añadimos el tipo de encuesta (ej: 'single', 'multiple', 'single_cat', 'multiple_cat')
            $table->string('type')->after('is_active');

            // Añadimos el máximo de selecciones, por defecto 1
            $table->integer('max_selections')->default(1)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            // Si algo sale mal, eliminamos las columnas
            $table->dropColumn(['type', 'max_selections']);
        });
    }
};
