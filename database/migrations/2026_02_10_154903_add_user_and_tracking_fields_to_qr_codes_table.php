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
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('scan_count')->default(0)->after('qr_image_path');
            $table->boolean('is_dynamic')->default(false)->after('scan_count')->comment('Premium: dynamic QR codes with editable content');
            $table->string('redirect_slug')->nullable()->unique()->after('is_dynamic')->comment('Short slug for dynamic QR redirect /r/{slug}');
            
            $table->index(['user_id', 'created_at']);
            $table->index('scan_count');
            $table->index('redirect_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'scan_count', 'is_dynamic', 'redirect_slug']);
        });
    }
};
