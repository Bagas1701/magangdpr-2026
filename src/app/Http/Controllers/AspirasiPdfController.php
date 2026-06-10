<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use Barryvdh\DomPDF\Facade\Pdf;

class AspirasiPdfController extends Controller
{
    public function show(Aspirasi $aspirasi)
    {
        abort_unless(auth()->user()?->canAccessAspirasiModule(), 403);

        $aspirasi->load([
            'konstituen',
            'kategoriAspirasi',
            'status',
            'creator',
            'approver',
            'notes.user',
            'attachments.uploader',
            'statusHistories',
        ]);

        $jenisKeputusan = match ($aspirasi->jenis_keputusan) {
            'disetujui_selesai' => 'Disetujui & Selesai',
            'disetujui_arsip' => 'Disetujui untuk Arsip',
            'revisi_data' => 'Revisi Data',
            'revisi_dokumen' => 'Revisi Dokumen',
            'revisi_tindak_lanjut' => 'Revisi Tindak Lanjut',
            'ditolak_data_tidak_valid' => 'Ditolak - Data Tidak Valid',
            'ditolak_bukan_kewenangan' => 'Ditolak - Bukan Kewenangan',
            'ditolak_bukti_tidak_cukup' => 'Ditolak - Bukti Tidak Cukup',
            'ditolak_duplikasi' => 'Ditolak - Duplikasi Aspirasi',
            default => '-',
        };

        $pdf = Pdf::loadView('pdf.aspirasi-report', [
            'aspirasi' => $aspirasi,
            'jenisKeputusan' => $jenisKeputusan,
        ])->setPaper('a4');

        return $pdf->stream('laporan-aspirasi-' . $aspirasi->id . '.pdf');
    }

    public function public(Aspirasi $aspirasi)
    {
        $aspirasi->load([
            'konstituen',
            'kategoriAspirasi',
            'status',
            'attachments',
            'statusHistories',
        ]);

        $pdf = Pdf::loadView('pdf.public-aspirasi-report', [
            'aspirasi' => $aspirasi,
        ])->setPaper('a4');

        return $pdf->stream('bukti-aspirasi-' . $aspirasi->ticket_number . '.pdf');
    }
}