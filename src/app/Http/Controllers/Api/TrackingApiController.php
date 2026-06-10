<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\JsonResponse;

class TrackingApiController extends Controller
{
    public function show(string $ticket_number): JsonResponse
    {
        $aspirasi = Aspirasi::query()
            ->with([
                'status',
                'kategoriAspirasi',
                'attachments',
                'statusHistories',
            ])
            ->where('ticket_number', $ticket_number)
            ->first();

        if (! $aspirasi) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor tiket tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,

            'data' => [
                'ticket_number' => $aspirasi->ticket_number,
                'judul' => $aspirasi->judul,
                'deskripsi' => $aspirasi->deskripsi,
                'prioritas' => $aspirasi->prioritas,

                'status' => $aspirasi->status?->nama,

                'kategori' => $aspirasi->kategoriAspirasi?->nama,

                'tanggal_kejadian' => $aspirasi->tanggal_kejadian,

                'lokasi_kejadian' => $aspirasi->lokasi_kejadian,

                'created_at' => $aspirasi->created_at,

                'attachments_count' => $aspirasi->attachments->count(),

                'timeline' => $aspirasi->statusHistories->map(
                    fn ($history) => [
                        'old_status' => $history->old_status,
                        'new_status' => $history->new_status,
                        'catatan' => $history->catatan,
                        'tanggal' => $history->created_at,
                    ]
                ),
            ],
        ]);
    }
}