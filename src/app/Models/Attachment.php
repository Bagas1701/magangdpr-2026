<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    public const STAGE_AWAL = 'awal';
    public const STAGE_TINDAK_LANJUT = 'tindak_lanjut';

    public const CATEGORY_SURAT_PENGADUAN = 'surat_pengaduan';
    public const CATEGORY_BUKTI_AWAL = 'bukti_awal';
    public const CATEGORY_FOTO_KEJADIAN = 'foto_kejadian';
    public const CATEGORY_DOKUMEN_PENDUKUNG = 'dokumen_pendukung';

    public const CATEGORY_SURAT_KOORDINASI = 'surat_koordinasi';
    public const CATEGORY_LAPORAN = 'laporan';
    public const CATEGORY_HASIL_MEDIASI = 'hasil_mediasi';
    public const CATEGORY_DOKUMENTASI_LANJUTAN = 'dokumentasi_lanjutan';

    protected $fillable = [
        'aspirasi_id',
        'status_id',
        'uploaded_by',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
        'stage',
        'attachment_category',
        'is_locked',
        'description',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    public static function stageOptions(): array
    {
        return [
            self::STAGE_AWAL => 'File Awal',
            self::STAGE_TINDAK_LANJUT => 'File Tindak Lanjut',
        ];
    }

    public static function initialCategoryOptions(): array
    {
        return [
            self::CATEGORY_SURAT_PENGADUAN => 'Surat Pengaduan',
            self::CATEGORY_BUKTI_AWAL => 'Bukti Awal',
            self::CATEGORY_FOTO_KEJADIAN => 'Foto Kejadian',
            self::CATEGORY_DOKUMEN_PENDUKUNG => 'Dokumen Pendukung',
        ];
    }

    public static function followUpCategoryOptions(): array
    {
        return [
            self::CATEGORY_SURAT_KOORDINASI => 'Surat Koordinasi',
            self::CATEGORY_LAPORAN => 'Laporan',
            self::CATEGORY_HASIL_MEDIASI => 'Hasil Mediasi',
            self::CATEGORY_DOKUMENTASI_LANJUTAN => 'Dokumentasi Lanjutan',
        ];
    }

    public static function categoryOptions(): array
    {
        return self::initialCategoryOptions() + self::followUpCategoryOptions();
    }

    public function aspirasi(): BelongsTo
    {
        return $this->belongsTo(Aspirasi::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusAspirasi::class, 'status_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function isAwal(): bool
    {
        return $this->stage === self::STAGE_AWAL;
    }

    public function isTindakLanjut(): bool
    {
        return $this->stage === self::STAGE_TINDAK_LANJUT;
    }

    public function isLocked(): bool
    {
        return (bool) $this->is_locked;
    }

    public function lock(): bool
    {
        return $this->update([
            'is_locked' => true,
        ]);
    }

    public function unlock(): bool
    {
        return $this->update([
            'is_locked' => false,
        ]);
    }
}