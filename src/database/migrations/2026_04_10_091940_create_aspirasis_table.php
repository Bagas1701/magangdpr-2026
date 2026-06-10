<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aspirasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('konstituen_id')
                ->constrained('konstituens')
                ->cascadeOnDelete();

            $table->foreignId('kategori_aspirasi_id')
                ->nullable()
                ->constrained('kategori_aspirasis')
                ->nullOnDelete();

            $table->foreignId('status_id')
                ->nullable()
                ->constrained('status_aspirasis')
                ->nullOnDelete();

            $table->string('judul');
            $table->text('deskripsi');

            $table->date('tanggal_kejadian')->nullable();
            $table->string('lokasi_kejadian')->nullable();
            $table->string('prioritas')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
                
            $table->string('kategori_lainnya')->nullable();

            $table->json('verification_checklist')->nullable();

            $table->string('approval_status')->default('pending');
            $table->text('approval_note')->nullable();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirasis');
    }
};