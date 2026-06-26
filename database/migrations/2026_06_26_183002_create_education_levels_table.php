<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedTinyInteger('min_grade');
            $table->unsignedTinyInteger('max_grade');
            $table->timestamps();
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->foreign('education_level_id')
                ->references('id')
                ->on('education_levels')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropForeign(['education_level_id']);
        });

        Schema::dropIfExists('education_levels');
    }
};
