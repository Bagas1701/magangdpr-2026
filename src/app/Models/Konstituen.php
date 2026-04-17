<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konstituen extends Model
{
    protected $fillable = [
        'nik',
        'nama',
        'kontak',
        'alamat',
        'wilayah',
    ];
    // Relasi dengan Aspirasi
    public function aspirasis()
    {
        return $this->hasMany(Aspirasi::class);
    }
}

