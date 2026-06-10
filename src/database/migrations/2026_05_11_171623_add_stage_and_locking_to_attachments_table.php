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
        Schema::table('attachments', function (Blueprint $table) {
            $table->string('stage')
                ->default('awal')
                ->after('file_size');

            $table->boolean('is_locked')
                ->default(false)
                ->after('attachment_category');

            $table->text('description')
                ->nullable()
                ->after('is_locked');

            $table->index(['aspirasi_id', 'stage']);
            $table->index(['stage', 'is_locked']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropIndex(['aspirasi_id', 'stage']);
            $table->dropIndex(['stage', 'is_locked']);

            $table->dropColumn([
                'stage',
                'is_locked',
                'description',
            ]);
        });
    }
};
