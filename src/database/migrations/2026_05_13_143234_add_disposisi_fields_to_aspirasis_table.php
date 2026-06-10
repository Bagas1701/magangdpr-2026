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
        Schema::table('aspirasis', function (Blueprint $table) {
            $table->string('nomor_disposisi')
                ->nullable()
                ->after('approval_note');

            $table->string('jenis_keputusan')
                ->nullable()
                ->after('nomor_disposisi');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aspirasis', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_disposisi',
                'jenis_keputusan',
            ]);
        });
    }
};
