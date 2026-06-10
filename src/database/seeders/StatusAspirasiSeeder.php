<?php

namespace Database\Seeders;

use App\Models\StatusAspirasi;
use Illuminate\Database\Seeder;

class StatusAspirasiSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'nama' => 'Masuk',
                'deskripsi' => 'Aspirasi baru dicatat oleh staf.',
                'urutan' => 1,
                'is_active' => true,
            ],
            [
                'nama' => 'Verifikasi',
                'deskripsi' => 'Aspirasi sedang diverifikasi oleh staf.',
                'urutan' => 2,
                'is_active' => true,
            ],
            [
                'nama' => 'Tindak Lanjut',
                'deskripsi' => 'Aspirasi sedang diproses oleh staf atau tenaga ahli.',
                'urutan' => 3,
                'is_active' => true,
            ],
            [
                'nama' => 'Menunggu Persetujuan',
                'deskripsi' => 'Aspirasi menunggu keputusan anggota dewan.',
                'urutan' => 4,
                'is_active' => true,
            ],
            [
                'nama' => 'Selesai',
                'deskripsi' => 'Aspirasi selesai dan disetujui anggota dewan.',
                'urutan' => 5,
                'is_active' => true,
            ],
            [
                'nama' => 'Ditolak',
                'deskripsi' => 'Aspirasi ditolak oleh anggota dewan.',
                'urutan' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($statuses as $status) {
            StatusAspirasi::updateOrCreate(
                ['nama' => $status['nama']],
                $status
            );
        }
    }
}