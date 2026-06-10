<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Schemas;

use App\Models\Aspirasi;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;

class ApprovalSection
{
    public static function make(): Section
    {
        return Section::make('Keputusan Anggota Dewan')
            ->description('Ringkasan keputusan, disposisi, dan catatan dari Anggota Dewan.')
            ->schema([
                Placeholder::make('approval_status')
                    ->label('Status Approval')
                    ->content(function (?Aspirasi $record): string {
                        return match ($record?->approval_status) {
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'revision' => 'Revisi',
                            default => 'Menunggu Persetujuan',
                        };
                    }),

                Placeholder::make('nomor_disposisi')
                    ->label('Nomor Disposisi')
                    ->content(fn (?Aspirasi $record): string => $record?->nomor_disposisi ?: '-'),

                Placeholder::make('jenis_keputusan')
                    ->label('Jenis Keputusan')
                    ->content(fn (?Aspirasi $record): string => match ($record?->jenis_keputusan) {
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
                    }),

                Placeholder::make('approved_by')
                    ->label('Diproses Oleh')
                    ->content(fn (?Aspirasi $record): string => $record?->approver?->name ?? '-'),

                Placeholder::make('approved_at')
                    ->label('Tanggal Keputusan')
                    ->content(fn (?Aspirasi $record): string =>
                        $record?->approved_at?->format('d M Y H:i') ?? '-'
                    ),

                Textarea::make('approval_note')
                    ->label(fn (?Aspirasi $record): string => match ($record?->approval_status) {
                        'revision' => 'Catatan Revisi',
                        'rejected' => 'Alasan Penolakan',
                        default => 'Catatan',
                    })
                    ->rows(5)
                    ->visible(fn (?Aspirasi $record): bool =>
                        in_array($record?->approval_status, ['revision', 'rejected'], true)
                    )
                    ->disabled()
                    ->columnSpanFull(),
            ])
            ->visible(fn (?Aspirasi $record): bool =>
                $record?->isMenungguPersetujuan()
                || $record?->isFinalState()
                || in_array($record?->approval_status, ['revision', 'approved', 'rejected'], true)
            )
            ->columns(3)
            ->columnSpanFull();
    }
}