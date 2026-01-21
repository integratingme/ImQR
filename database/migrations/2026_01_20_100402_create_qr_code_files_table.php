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
        Schema::create('qr_code_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_code_id')->constrained()->onDelete('cascade');
            $table->string('file_type'); // pdf, image, menu, etc.
            $table->string('file_path');
            $table->string('original_name');
            $table->unsignedBigInteger('file_size')->nullable(); // in bytes
            $table->timestamps();
            
            $table->index('qr_code_id');
            $table->index('file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_code_files');
    }
};
