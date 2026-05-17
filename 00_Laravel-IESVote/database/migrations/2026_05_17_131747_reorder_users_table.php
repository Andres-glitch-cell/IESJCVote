<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Renombrar si tienes "nombre"
            if (Schema::hasColumn('users', 'nombre')) {
                $table->renameColumn('nombre', 'name');
            }

            // Reordenar no se puede directamente en Laravel,
            // pero puedes recrear la tabla si quieres orden perfecto
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};

?>