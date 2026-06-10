<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\KategoriAspirasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriAspirasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'nama' => 'Pidana',
                'deskripsi' => 'Aspirasi terkait perkara pidana.',
            ],
            [
                'nama' => 'Perdata',
                'deskripsi' => 'Aspirasi terkait perkara perdata.',
            ],
            [
                'nama' => 'HAM',
                'deskripsi' => 'Aspirasi terkait hak asasi manusia.',
            ],
            [
                'nama' => 'Agraria',
                'deskripsi' => 'Aspirasi terkait tanah dan pertanahan.',
            ],
            [
                'nama' => 'Lainnya',
                'deskripsi' => 'Aspirasi umum di luar kategori utama.',
            ],
        ];

        foreach ($items as $item) {
            KategoriAspirasi::updateOrCreate(
                ['slug' => Str::slug($item['nama'])],
                [
                    'nama' => $item['nama'],
                    'slug' => Str::slug($item['nama']),
                    'deskripsi' => $item['deskripsi'],
                    'is_active' => true,
                ]
            );
        }
    }
}
