<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('historials', function (Blueprint $table) {
            $table->id(); // idHistorial
            $table->foreignId('user_id')->constrained('users'); // idUsuario
            $table->string('action'); // ACCIÓN: LOGIN, VOTO_EMITIDO, etc.
            $table->timestamp('fechaRegistro'); // fechaRegistro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historials');
    }
};