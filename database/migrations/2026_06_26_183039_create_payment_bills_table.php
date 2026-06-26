<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_type_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('due_date')->nullable();
            $table->string('period')->nullable();
            $table->string('status')->default('unpaid');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_bills');
    }
};
