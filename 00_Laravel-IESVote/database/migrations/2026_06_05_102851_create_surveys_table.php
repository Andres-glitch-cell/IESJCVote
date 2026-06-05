<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['single', 'single_cat', 'multiple', 'multiple_cat'])->default('single');
            $table->integer('max_selections')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_real_time_enabled')->default(false);
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};