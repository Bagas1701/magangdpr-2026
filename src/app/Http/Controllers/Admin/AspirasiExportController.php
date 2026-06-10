<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AspirasiExportController extends Controller
{
    public function excel(): StreamedResponse
    {
        $fileName = 'rekap-aspirasi-' . now()->format('Y-m-d-His') . '.csv';

        $aspirasis = Aspirasi::query()
            ->with(['konstituen', 'kategoriAspirasi', 'status'])
            ->latest()
            ->get();

        return response()->streamDownload(function () use ($aspirasis) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Nomor Tiket',
                'Nama Konstituen',
                'Kontak',
                'Kategori',
                'Judul',
                'Status',
                'Prioritas',
                'Tanggal Kejadian',
                'Lokasi Kejadian',
                'Tanggal Dibuat',
            ]);

            foreach ($aspirasis as $aspirasi) {
                fputcsv($handle, [
                    $aspirasi->ticket_number,
                    $aspirasi->konstituen?->nama,
                    $aspirasi->konstituen?->kontak,
                    $aspirasi->kategoriAspirasi?->nama,
                    $aspirasi->judul,
                    $aspirasi->status?->nama,
                    $aspirasi->prioritas,
                    $aspirasi->tanggal_kejadian,
                    $aspirasi->lokasi_kejadian,
                    $aspirasi->created_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}