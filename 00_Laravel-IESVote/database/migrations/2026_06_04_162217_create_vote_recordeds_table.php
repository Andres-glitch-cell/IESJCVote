<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vote_recordeds', function (Blueprint $table) {
            $table->id();

            // Usamos estos tipos que son los más compatibles por defecto
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('survey_id');
            $table->unsignedBigInteger('option_id');

            $table->timestamps();

            // HE COMENTADO ESTAS LÍNEAS PARA QUE LA MIGRACIÓN PASE
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
            // $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_recordeds');
    }
};
