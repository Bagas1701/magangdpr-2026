<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusAspirasi extends Model
{
    protected $table = 'status_aspirasis';

    protected $fillable = [
        'nama',
        'deskripsi',
        'urutan',
        'is_active',
    ];

    public function aspirasis(): HasMany
    {
        return $this->hasMany(Aspirasi::class, 'status_id');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(AspirasiStatusHistory::class, 'status_id');
    }
}
