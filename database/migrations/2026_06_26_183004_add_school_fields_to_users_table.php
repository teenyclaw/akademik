<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('profile_type')->nullable()->after('password');
            $table->unsignedBigInteger('profile_id')->nullable()->after('profile_type');
            $table->boolean('is_active')->default(true)->after('profile_id');
            $table->boolean('is_super_admin')->default(false)->after('is_active');
            $table->softDeletes();

            $table->index(['profile_type', 'profile_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropIndex(['profile_type', 'profile_id']);
            $table->dropColumn([
                'school_id',
                'profile_type',
                'profile_id',
                'is_active',
                'is_super_admin',
                'deleted_at',
            ]);
        });
    }
};
