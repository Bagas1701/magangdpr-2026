<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Widgets;

use App\Models\Aspirasi;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class ActivityTimelineWidget extends Widget
{
    protected static string $view = 'filament.admin.resources.aspirasi-resource.widgets.activity-timeline-widget';

    public ?Aspirasi $record = null;

    protected int | string | array $columnSpan = 'full';

    public function getActivities(): Collection
    {
        if (! $this->record) {
            return collect();
        }

        $aspirasi = $this->record->loadMissing([
            'creator',
            'approver',
            'statusHistories.changer',
            'attachments.uploader',
            'notes.user',
        ]);

        $activities = collect();

        $activities->push([
            'time' => $aspirasi->created_at,
            'title' => 'Aspirasi dibuat',
            'description' => 'Aspirasi pertama kali dicatat ke dalam sistem.',
            'actor' => $aspirasi->creator?->name ?? 'System',
            'type' => 'create',
        ]);

        foreach ($aspirasi->statusHistories as $history) {
            $activities->push([
                'time' => $history->created_at,
                'title' => "Status berubah: {$history->old_status} → {$history->new_status}",
                'description' => $history->catatan ?? '-',
                'actor' => $history->changer?->name ?? 'System',
                'type' => 'status',
            ]);
        }

        foreach ($aspirasi->attachments as $attachment) {
            $activities->push([
                'time' => $attachment->created_at,
                'title' => 'Lampiran diunggah',
                'description' => $attachment->original_name,
                'actor' => $attachment->uploader?->name ?? 'System',
                'type' => 'attachment',
            ]);
        }

        foreach ($aspirasi->notes as $note) {
            $activities->push([
                'time' => $note->created_at,
                'title' => 'Kajian / rekomendasi ditambahkan',
                'description' => $note->catatan,
                'actor' => $note->user?->name ?? 'System',
                'type' => 'note',
            ]);
        }

        if ($aspirasi->approved_at) {
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
                default => 'Keputusan Anggota Dewan',
            };

            $description = collect([
                $aspirasi->nomor_disposisi ? 'Nomor disposisi: ' . $aspirasi->nomor_disposisi : null,
                'Jenis keputusan: ' . $jenisKeputusan,
                $aspirasi->approval_note ? 'Catatan: ' . $aspirasi->approval_note : null,
            ])->filter()->implode(' | ');

            $activities->push([
                'time' => $aspirasi->approved_at,
                'title' => match ($aspirasi->approval_status) {
                    'approved' => 'Aspirasi disetujui',
                    'revision' => 'Aspirasi diminta revisi',
                    'rejected' => 'Aspirasi ditolak',
                    default => 'Keputusan anggota dewan',
                },
                'description' => $description,
                'actor' => $aspirasi->approver?->name ?? 'Anggota Dewan',
                'type' => 'approval',
            ]);
        }

        return $activities
            ->sortByDesc('time')
            ->values();
    }
}