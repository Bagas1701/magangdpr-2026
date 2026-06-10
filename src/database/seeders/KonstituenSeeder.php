<?php

namespace Database\Seeders;

use App\Models\Konstituen;
use Illuminate\Database\Seeder;

class KonstituenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nik' => '3174010101010001',
                'nama' => 'Budi Santoso',
                'kontak' => '081234560001',
                'alamat' => 'Jl. Mawar No. 10',
                'kabupaten_kota' => 'Kota Pematangsiantar',
                'kecamatan' => 'Siantar Barat',
                'kelurahan' => 'ST Barat',
            ],

            [
                'nik' => '3174010101010002',
                'nama' => 'Siti Aisyah',
                'kontak' => '081234560002',
                'alamat' => 'Jl. Melati No. 22',
                'kabupaten_kota' => 'Kabupaten Simalungun',
                'kecamatan' => 'Raya',
                'kelurahan' => 'Raya Tengah',
            ],

            [
                'nik' => '3174010101010003',
                'nama' => 'Andi Saputra',
                'kontak' => '081234560003',
                'alamat' => 'Jl. Kenanga No. 7',
                'kabupaten_kota' => 'Kabupaten Asahan',
                'kecamatan' => 'Kisaran Timur',
                'kelurahan' => 'Mutiara',
            ],
        ];

        foreach ($data as $item) {
            Konstituen::updateOrCreate(
                ['nik' => $item['nik']],
                $item
            );
        }
    }
}