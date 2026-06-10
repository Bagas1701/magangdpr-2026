<?php

namespace Database\Seeders;

use App\Models\Aspirasi;
use App\Models\KategoriAspirasi;
use App\Models\Konstituen;
use App\Models\StatusAspirasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class AspirasiSeeder extends Seeder
{
    public function run(): void
    {
        $statusMasuk = StatusAspirasi::where('nama', Aspirasi::STATUS_MASUK)->first();
        $statusSelesai = StatusAspirasi::where('nama', Aspirasi::STATUS_SELESAI)->first();
        $statusTolak = StatusAspirasi::where('nama', Aspirasi::STATUS_DITOLAK)->first();

        $kategori = KategoriAspirasi::first();
        $admin = User::first();
        $konstituens = Konstituen::all();

        if (! $kategori || ! $admin || $konstituens->isEmpty()) {
            return;
        }

        $data = [
            [
                'judul' => 'Permohonan mediasi sengketa tanah keluarga',
                'deskripsi' => 'Konstituen meminta pendampingan awal terkait sengketa sertifikat tanah.',
                'status_id' => $statusSelesai?->id,
                'prioritas' => 'rendah',
                'approval_status' => 'approved',
                'nomor_disposisi' => 'DISP/KOMIII/2026/001',
                'jenis_keputusan' => 'disetujui_selesai',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
            [
                'judul' => 'Laporan dugaan penipuan investasi bodong',
                'deskripsi' => 'Permintaan bantuan konsultasi hukum atas dugaan penipuan investasi online.',
                'status_id' => $statusMasuk?->id,
                'prioritas' => 'sedang',
            ],
            [
                'judul' => 'Permohonan bantuan kasus pencemaran nama baik',
                'deskripsi' => 'Permintaan pendampingan terkait laporan pencemaran nama baik di media sosial.',
                'status_id' => $statusMasuk?->id,
                'prioritas' => 'tinggi',
            ],
            [
                'judul' => 'Permohonan perlindungan hukum UMKM',
                'deskripsi' => 'Pelaku UMKM meminta bantuan hukum terkait sengketa usaha.',
                'status_id' => $statusMasuk?->id,
                'prioritas' => 'sedang',
            ],
            [
                'judul' => 'Laporan dugaan pungli pelayanan publik',
                'deskripsi' => 'Laporan masyarakat terkait dugaan pungutan liar.',
                'status_id' => $statusTolak?->id,
                'prioritas' => 'mendesak',
                'approval_status' => 'rejected',
                'approval_note' => 'Aspirasi ditolak karena bukti pendukung belum mencukupi.',
                'nomor_disposisi' => 'DISP/KOMIII/2026/002',
                'jenis_keputusan' => 'ditolak_bukti_tidak_cukup',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ],
        ];

        foreach ($data as $index => $item) {
            Aspirasi::create([
                'konstituen_id' => $konstituens[$index % $konstituens->count()]->id,
                'kategori_aspirasi_id' => $kategori->id,
                'status_id' => $item['status_id'],
                'judul' => $item['judul'],
                'deskripsi' => $item['deskripsi'],
                'tanggal_kejadian' => now()->subDays(rand(1, 30)),
                'lokasi_kejadian' => 'Sumatera Utara',
                'prioritas' => $item['prioritas'],
                'created_by' => $admin->id,
                'approval_status' => $item['approval_status'] ?? 'pending',
                'approval_note' => $item['approval_note'] ?? null,
                'nomor_disposisi' => $item['nomor_disposisi'] ?? null,
                'jenis_keputusan' => $item['jenis_keputusan'] ?? null,
                'approved_by' => $item['approved_by'] ?? null,
                'approved_at' => $item['approved_at'] ?? null,
            ]);
        }
    }
}