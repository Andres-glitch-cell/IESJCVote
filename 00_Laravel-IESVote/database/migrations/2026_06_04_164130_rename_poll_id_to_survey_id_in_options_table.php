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
        Schema::table('options', function (Blueprint $table) {
            $table->renameColumn('poll_id', 'survey_id');
        });
    }

    public function down(): void
    {
        Schema::table('options', function (Blueprint $table) {
            $table->renameColumn('survey_id', 'poll_id');
        });
    }
};