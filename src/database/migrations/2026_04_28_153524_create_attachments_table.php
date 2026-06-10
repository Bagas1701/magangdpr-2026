<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('aspirasi_id')
                ->constrained('aspirasis')
                ->cascadeOnDelete();

            $table->foreignId('status_id')
                ->constrained('status_aspirasis')
                ->cascadeOnDelete();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('attachment_category')->default('dokumen_awal');
            $table->timestamps();

            $table->index(['aspirasi_id', 'status_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};