<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vote_recordeds', function (Blueprint $table) {
            // Añadimos la columna para el hash del voto
            $table->string('vote_hash')->after('survey_id');
        });
    }

    public function down(): void
    {
        Schema::table('vote_recordeds', function (Blueprint $table) {
            $table->dropColumn('vote_hash');
        });
    }
};
