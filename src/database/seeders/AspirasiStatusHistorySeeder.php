<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aspirasi;
use App\Models\AspirasiStatusHistory;
use App\Models\User;

class AspirasiStatusHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staf = User::where('email', 'staf@magangdpr.test')->first();
        $tenagaAhli = User::where('email', 'tenagaahli@magangdpr.test')->first();
        $anggota = User::where('email', 'anggota@magangdpr.test')->first();

        $map = [
            'Permohonan mediasi sengketa tanah keluarga' => [
                ['old_status' => null, 'new_status' => 'Masuk', 'changed_by' => $staf?->id, 'catatan' => 'Status awal saat aspirasi dibuat'],
            ],
            'Laporan dugaan penipuan investasi' => [
                ['old_status' => null, 'new_status' => 'Masuk', 'changed_by' => $staf?->id, 'catatan' => 'Status awal saat aspirasi dibuat'],
                ['old_status' => 'Masuk', 'new_status' => 'Verifikasi', 'changed_by' => $tenagaAhli?->id, 'catatan' => 'Dokumen awal diterima untuk diverifikasi'],
            ],
            'Permasalahan wanprestasi kontrak kerja sama' => [
                ['old_status' => null, 'new_status' => 'Masuk', 'changed_by' => $staf?->id, 'catatan' => 'Status awal saat aspirasi dibuat'],
                ['old_status' => 'Masuk', 'new_status' => 'Verifikasi', 'changed_by' => $tenagaAhli?->id, 'catatan' => 'Masuk tahap verifikasi dokumen'],
                ['old_status' => 'Verifikasi', 'new_status' => 'Tindak Lanjut', 'changed_by' => $anggota?->id, 'catatan' => 'Aspirasi dilanjutkan ke tahap kajian'],
            ],
            'Pengaduan dugaan pelanggaran hak asasi' => [
                ['old_status' => null, 'new_status' => 'Masuk', 'changed_by' => $staf?->id, 'catatan' => 'Status awal saat aspirasi dibuat'],
                ['old_status' => 'Masuk', 'new_status' => 'Verifikasi', 'changed_by' => $tenagaAhli?->id, 'catatan' => 'Verifikasi awal selesai'],
                ['old_status' => 'Verifikasi', 'new_status' => 'Tindak Lanjut', 'changed_by' => $anggota?->id, 'catatan' => 'Dilanjutkan ke penelaahan'],
                ['old_status' => 'Tindak Lanjut', 'new_status' => 'Selesai', 'changed_by' => $anggota?->id, 'catatan' => 'Penanganan dinyatakan selesai'],
            ],
        ];

        foreach ($map as $judul => $rows) {
            $aspirasi = Aspirasi::where('judul', $judul)->first();

            if (! $aspirasi) {
                continue;
            }

            AspirasiStatusHistory::where('aspirasi_id', $aspirasi->id)->delete();

            foreach ($rows as $row) {
                AspirasiStatusHistory::create([
                    'aspirasi_id' => $aspirasi->id,
                    'old_status' => $row['old_status'],
                    'new_status' => $row['new_status'],
                    'changed_by' => $row['changed_by'],
                    'catatan' => $row['catatan'],
                ]);
            }
        }
    }
}
