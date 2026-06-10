<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aspirasi extends Model
{
    public const STATUS_MASUK = 'Masuk';
    public const STATUS_VERIFIKASI = 'Verifikasi';
    public const STATUS_TINDAK_LANJUT = 'Tindak Lanjut';
    public const STATUS_MENUNGGU_PERSETUJUAN = 'Menunggu Persetujuan';
    public const STATUS_SELESAI = 'Selesai';
    public const STATUS_DITOLAK = 'Ditolak';

    public ?string $statusChangeNote = null;

    protected $fillable = [
        'ticket_number',
        'konstituen_id',
        'kategori_aspirasi_id',
        'status_id',
        'judul',
        'deskripsi',
        'tanggal_kejadian',
        'lokasi_kejadian',
        'prioritas',
        'created_by',
        'kategori_lainnya',
        'approval_status',
        'approval_note',
        'nomor_disposisi',
        'jenis_keputusan',
        'approved_by',
        'approved_at',
        'verification_checklist',
    ];

    protected $casts = [
        'verification_checklist' => 'array',
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Aspirasi $aspirasi): void {

            if (blank($aspirasi->ticket_number)) {

                $year = now()->year;

                $latest = self::whereYear('created_at', $year)
                    ->count() + 1;

                $aspirasi->ticket_number = sprintf(
                    'ASP-%s-%04d',
                    $year,
                    $latest
                );
            }

            if (! $aspirasi->status_id) {

                $defaultStatus = StatusAspirasi::where(
                    'nama',
                    self::STATUS_MASUK
                )->first();

                $aspirasi->status_id = $defaultStatus?->id;
            }
        });

        static::created(function (Aspirasi $aspirasi): void {

            AspirasiStatusHistory::create([
                'aspirasi_id' => $aspirasi->id,
                'old_status' => '-',
                'new_status' => self::STATUS_MASUK,
                'changed_by' => auth()->id(),
                'catatan' => 'Aspirasi dibuat dan masuk ke tahap awal.',
            ]);
        });

        static::updated(function (Aspirasi $aspirasi): void {

            if (! $aspirasi->wasChanged('status_id')) {
                return;
            }

            $oldStatusId = $aspirasi->getOriginal('status_id');

            $oldStatusName = StatusAspirasi::find($oldStatusId)?->nama ?? '-';

            $newStatusName = StatusAspirasi::find(
                $aspirasi->status_id
            )?->nama ?? '-';

            AspirasiStatusHistory::create([
                'aspirasi_id' => $aspirasi->id,
                'old_status' => $oldStatusName,
                'new_status' => $newStatusName,
                'changed_by' => auth()->id(),
                'catatan' => $aspirasi->statusChangeNote
                    ?: 'Status aspirasi diperbarui',
            ]);
        });
    }

    public function konstituen(): BelongsTo
    {
        return $this->belongsTo(Konstituen::class);
    }

    public function kategoriAspirasi(): BelongsTo
    {
        return $this->belongsTo(
            KategoriAspirasi::class,
            'kategori_aspirasi_id'
        );
    }

    public function statusAspirasi(): BelongsTo
    {
        return $this->belongsTo(
            StatusAspirasi::class,
            'status_id'
        );
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(
            StatusAspirasi::class,
            'status_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(AspirasiStatusHistory::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(AspirasiNote::class);
    }

    public function currentStatusName(): ?string
    {
        return $this->statusAspirasi?->nama;
    }

    public function isMasuk(): bool
    {
        return $this->currentStatusName() === self::STATUS_MASUK;
    }

    public function isVerifikasi(): bool
    {
        return $this->currentStatusName() === self::STATUS_VERIFIKASI;
    }

    public function isTindakLanjut(): bool
    {
        return $this->currentStatusName() === self::STATUS_TINDAK_LANJUT;
    }

    public function isMenungguPersetujuan(): bool
    {
        return $this->currentStatusName() === self::STATUS_MENUNGGU_PERSETUJUAN;
    }

    public function isSelesai(): bool
    {
        return $this->currentStatusName() === self::STATUS_SELESAI;
    }

    public function isDitolak(): bool
    {
        return $this->currentStatusName() === self::STATUS_DITOLAK;
    }

    public function isFinalState(): bool
    {
        return $this->isSelesai() || $this->isDitolak();
    }

    public function canEditTahapAwal(): bool
    {
        return $this->isMasuk() || $this->isVerifikasi();
    }

    public function canProcessTindakLanjut(): bool
    {
        return $this->isTindakLanjut();
    }

    public function needsApproval(): bool
    {
        return $this->isMenungguPersetujuan();
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isRejectedApproval(): bool
    {
        return $this->approval_status === 'rejected';
    }

    public function isRevisionRequested(): bool
    {
        return $this->approval_status === 'revision';
    }

    public function hasDisposisi(): bool
    {
        return filled($this->nomor_disposisi);
    }

    public function changeStatus(
        string $statusName,
        ?string $note = null
    ): bool {
        $status = StatusAspirasi::where(
            'nama',
            $statusName
        )->first();

        if (! $status) {
            return false;
        }

        $this->statusChangeNote = $note;

        $this->update([
            'status_id' => $status->id,
        ]);

        return true;
    }
}