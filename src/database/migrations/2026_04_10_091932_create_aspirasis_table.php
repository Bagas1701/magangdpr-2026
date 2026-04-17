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

            $table->foreignId('konstituen_id')->constrained()->cascadeOnDelete();

            $table->foreignId('kategori_aspirasi_id')
                ->nullable()
                ->constrained('kategori_aspirasis')
                ->nullOnDelete();

            $table->string('judul');
            $table->text('deskripsi');

            $table->enum('status', [
                'Masuk',
                'Verifikasi',
                'Tindak Lanjut',
                'Selesai'
            ])->default('Masuk');

            $table->string('file_bukti')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirasis');
    }
};