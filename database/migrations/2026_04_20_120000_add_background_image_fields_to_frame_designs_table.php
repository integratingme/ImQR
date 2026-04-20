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
        Schema::table('frame_designs', function (Blueprint $table) {
            $table->longText('background_image')->nullable()->after('svg_content');
            $table->string('background_image_fit', 20)->nullable()->after('background_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frame_designs', function (Blueprint $table) {
            $table->dropColumn(['background_image', 'background_image_fit']);
        });
    }
};
