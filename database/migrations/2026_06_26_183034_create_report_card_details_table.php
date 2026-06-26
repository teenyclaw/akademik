<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_card_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('grade_letter', 5)->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['report_card_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_card_details');
    }
};
