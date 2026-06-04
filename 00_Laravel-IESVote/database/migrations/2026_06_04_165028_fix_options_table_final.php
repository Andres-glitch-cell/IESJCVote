<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('options', function (Blueprint $table) {
            // 1. Esto se queda, es seguro (solo se ejecuta si la columna existe)
            if (Schema::hasColumn('options', 'poll_id')) {
                $table->renameColumn('poll_id', 'survey_id');
            }

            // 2. Esto se queda (asegura el tipo de dato)
            $table->unsignedBigInteger('survey_id')->change();

            // 3. COMENTA ESTO: Como ya creaste la FK manualmente, no la necesitas aquí.
            /*
            $table->foreign('survey_id')
                  ->references('id')
                  ->on('surveys')
                  ->onDelete('cascade');
            */
        });
    }

    public function down(): void
    {
        Schema::table('options', function (Blueprint $table) {
            // COMENTA ESTO: Si la intentas borrar, te dará error porque Laravel
            // no la creó a través de sus migraciones.
            // $table->dropForeign(['survey_id']);

            $table->renameColumn('survey_id', 'poll_id');
        });
    }
};
