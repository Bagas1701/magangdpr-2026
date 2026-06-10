<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardStatisticApiController extends Controller
{
    public function index(): JsonResponse
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

        return response()->json([
            'success' => true,
            'message' => 'Statistik dashboard berhasil diambil.',
            'data' => [
                'total_aspirasi' => $total,
                'status' => [
                    'masuk' => $byStatus['Masuk'] ?? 0,
                    'verifikasi' => $byStatus['Verifikasi'] ?? 0,
                    'tindak_lanjut' => $byStatus['Tindak Lanjut'] ?? 0,
                    'menunggu_persetujuan' => $byStatus['Menunggu Persetujuan'] ?? 0,
                    'selesai' => $byStatus['Selesai'] ?? 0,
                    'ditolak' => $byStatus['Ditolak'] ?? 0,
                ],
                'kategori' => $byCategory,
            ],
        ]);
    }
}
