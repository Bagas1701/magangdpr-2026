<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function show(Request $request)
    {
        $ticketNumber = $request->query('ticket_number');

        $aspirasi = null;
        $progress = 0;

        if ($ticketNumber) {
            $aspirasi = Aspirasi::query()
                ->with([
                    'status',
                    'kategoriAspirasi',
                    'attachments',
                    'statusHistories' => fn ($query) => $query->latest(),
                    'statusHistories.changer',
                ])
                ->where('ticket_number', $ticketNumber)
                ->first();

            if ($aspirasi) {
                $progressMap = [
                    'Masuk' => 20,
                    'Verifikasi' => 40,
                    'Tindak Lanjut' => 70,
                    'Menunggu Persetujuan' => 90,
                    'Selesai' => 100,
                    'Ditolak' => 100,
                ];

                $progress = $progressMap[$aspirasi->status?->nama] ?? 0;
            }
        }

        return view('frontend.tracking.show', [
            'ticketNumber' => $ticketNumber,
            'aspirasi' => $aspirasi,
            'progress' => $progress,
        ]);
    }
}
