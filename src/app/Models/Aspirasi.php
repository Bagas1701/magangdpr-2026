<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aspirasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'konstituen_id',
        'judul',
        'deskripsi',
        'kategori_aspirasi_id',
        'status',
        'file_bukti',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_note',
    ];

    public function konstituen()
    {
        return $this->belongsTo(Konstituen::class);
    }

    public function kategoriAspirasi()
    {
        return $this->belongsTo(KategoriAspirasi::class, 'kategori_aspirasi_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(AspirasiStatusHistory::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function canMoveToStatus(string $newStatus, $user): bool
    {
        $current = $this->status;

        $allowedTransitions = [
            'Masuk' => ['Verifikasi'],
            'Verifikasi' => ['Tindak Lanjut'],
            'Tindak Lanjut' => ['Selesai'],
            'Selesai' => [],
        ];

        if (! in_array($newStatus, $allowedTransitions[$current] ?? [])) {
            return false;
        }

        if (
            $newStatus === 'Selesai' &&
            ! $user->hasAnyRole(['anggota', 'admin', 'super_admin'])
        ) {
            return false;
        }

        return true;
    }
}