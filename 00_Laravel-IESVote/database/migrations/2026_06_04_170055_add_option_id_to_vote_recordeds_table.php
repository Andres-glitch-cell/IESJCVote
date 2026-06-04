<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vote_recordeds', function (Blueprint $table) {
            // Aseguramos que la columna exista y acepte el valor
            $table->unsignedBigInteger('option_id')->after('survey_id');
        });
    }

    public function down(): void
    {
        Schema::table('vote_recordeds', function (Blueprint $table) {
            $table->dropColumn('option_id');
        });
    }
};
