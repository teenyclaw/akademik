<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_card_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_card_id')->constrained()->cascadeOnDelete();
            $table->string('role');
            $table->string('name');
            $table->string('signature_image')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();

            $table->unique(['report_card_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_card_signatures');
    }
};
