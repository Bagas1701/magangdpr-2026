<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Konstituen extends Model
{
    protected $fillable = [
        'nik',
        'nama',
        'kontak',
        'alamat',
        'kabupaten_kota',
        'kecamatan',
        'kelurahan',
        'foto_ktp',
    ];

    public function aspirasis(): HasMany
    {
        return $this->hasMany(Aspirasi::class);
    }

    public function latestAspirasi(): HasOne
    {
        return $this->hasOne(Aspirasi::class)->latestOfMany();
    }

    public function getBadgeStatusAttribute(): string
    {
        $total = $this->aspirasis_count ?? $this->aspirasis()->count();

        return match (true) {
            $total === 0 => 'Belum Ada Aspirasi',
            $total <= 2 => 'Aktif',
            default => 'Sering Melapor',
        };
    }
}