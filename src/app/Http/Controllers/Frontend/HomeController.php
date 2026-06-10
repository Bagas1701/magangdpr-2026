<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\KategoriAspirasi;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $kategoriAspirasis = KategoriAspirasi::query()
            ->where('is_active', true)
            ->orderBy('nama')
            ->get();

        $totalAspirasi = Aspirasi::count();

        $statusCounts = Aspirasi::query()
            ->selectRaw('status_aspirasis.nama as status, COUNT(*) as total')
            ->leftJoin('status_aspirasis', 'aspirasis.status_id', '=', 'status_aspirasis.id')
            ->groupBy('status_aspirasis.nama')
            ->pluck('total', 'status');

        $processedAspirasi =
            ($statusCounts['Verifikasi'] ?? 0)
            + ($statusCounts['Tindak Lanjut'] ?? 0)
            + ($statusCounts['Menunggu Persetujuan'] ?? 0);

        $finishedAspirasi = $statusCounts['Selesai'] ?? 0;

        $dashboardStatusBars = [
            'Masuk' => $statusCounts['Masuk'] ?? 0,
            'Verifikasi' => $statusCounts['Verifikasi'] ?? 0,
            'Tindak Lanjut' => $statusCounts['Tindak Lanjut'] ?? 0,
            'Selesai' => $statusCounts['Selesai'] ?? 0,
            'Ditolak' => $statusCounts['Ditolak'] ?? 0,
        ];

        $latestAspirasis = Aspirasi::query()
            ->with(['status'])
            ->latest()
            ->limit(3)
            ->get(['id', 'ticket_number', 'status_id']);

        $completionRate = $totalAspirasi > 0
            ? round(($finishedAspirasi / $totalAspirasi) * 100)
            : 0;

        $currentMonthAspirasi = Aspirasi::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $topCategories = Aspirasi::query()
            ->selectRaw('kategori_aspirasis.nama as kategori, COUNT(*) as total')
            ->leftJoin('kategori_aspirasis', 'aspirasis.kategori_aspirasi_id', '=', 'kategori_aspirasis.id')
            ->groupBy('kategori_aspirasis.nama')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        return view('frontend.home', compact(
            'kategoriAspirasis',
            'totalAspirasi',
            'processedAspirasi',
            'finishedAspirasi',
            'dashboardStatusBars',
            'latestAspirasis',
            'completionRate',
            'currentMonthAspirasi',
            'topCategories',
        ));
    }
}