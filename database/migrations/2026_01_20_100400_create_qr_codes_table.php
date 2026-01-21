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
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // url, email, text, pdf, menu, coupon, event, app, location, wifi, phone
            $table->string('name');
            $table->json('data'); // Store all QR code specific data
            $table->json('colors')->nullable(); // Store primary and secondary colors
            $table->string('qr_image_path')->nullable(); // Path to generated QR code image
            $table->timestamps();
            
            $table->index('type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
