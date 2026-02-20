<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('plan', ['free', 'premium'])->default('free')->after('email_verified_at');
            $table->timestamp('plan_expires_at')->nullable()->after('plan');
            $table->integer('custom_logo_count')->default(0)->after('plan_expires_at')->comment('Count of QR codes with custom logo for free users (max 1)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['plan', 'plan_expires_at', 'custom_logo_count']);
        });
    }
};
