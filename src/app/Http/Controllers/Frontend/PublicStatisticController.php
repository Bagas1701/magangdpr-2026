<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\View\View;

class PublicStatisticController extends Controller
{
    public function index(): View
    {
        $total = Aspirasi::count();

        $byStatus = Aspirasi::query()
            ->selectRaw('status_aspirasis.nama as status, COUNT(*) as total')
            ->leftJoin('status_aspirasis', 'aspirasis.status_id', '=', 'status_aspirasis.id')
            ->groupBy('status_aspirasis.nama')
            ->pluck('total', 'status');

        $byCategory = Aspirasi::query()
            ->selectRaw('kategori_aspirasis.nama as kategori, COUNT(*) as total')
            ->leftJoin('kategori_aspirasis', 'aspirasis.kategori_aspirasi_id', '=', 'kategori_aspirasis.id')
            ->groupBy('kategori_aspirasis.nama')
            ->pluck('total', 'kategori');

        return view('frontend.statistics.index', [
            'total' => $total,
            'status' => [
                'masuk' => $byStatus['Masuk'] ?? 0,
                'verifikasi' => $byStatus['Verifikasi'] ?? 0,
                'tindak_lanjut' => $byStatus['Tindak Lanjut'] ?? 0,
                'menunggu_persetujuan' => $byStatus['Menunggu Persetujuan'] ?? 0,
                'selesai' => $byStatus['Selesai'] ?? 0,
                'ditolak' => $byStatus['Ditolak'] ?? 0,
            ],
            'categories' => $byCategory,
        ]);
    }
}