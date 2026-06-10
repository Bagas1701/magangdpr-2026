<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Schemas;

use App\Models\Aspirasi;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;

class VerificationChecklistSection
{
    public static function make(): Section
    {
        return Section::make('Checklist Verifikasi Dokumen')
            ->description('Digunakan staf untuk memastikan kelengkapan dokumen sebelum aspirasi dilanjutkan ke tahap Tindak Lanjut.')
            ->schema([
                CheckboxList::make('verification_checklist')
                    ->label('Dokumen yang sudah diverifikasi')
                    ->options([
                        'ktp' => new \Illuminate\Support\HtmlString(
                            'KTP / Identitas Konstituen
                            <span title="Identitas konstituen telah diterima dan dapat digunakan untuk verifikasi data pelapor." style="cursor: help; color: #6b7280;">
                                ⓘ
                            </span>'
                        ),

                        'surat_pengaduan' => new \Illuminate\Support\HtmlString(
                            'Surat Pengaduan
                            <span title="Dokumen pengaduan resmi dari konstituen telah diunggah." style="cursor: help; color: #6b7280;">
                                ⓘ
                            </span>'
                        ),

                        'bukti_pendukung' => new \Illuminate\Support\HtmlString(
                            'Bukti Pendukung
                            <span title="Dokumen atau foto pendukung terkait aspirasi telah tersedia." style="cursor: help; color: #6b7280;">
                                ⓘ
                            </span>'
                        ),

                        'data_konstituen_sesuai' => new \Illuminate\Support\HtmlString(
                            'Data Konstituen Sesuai
                            <span title="Data konstituen telah diverifikasi oleh staf dan dinyatakan valid." style="cursor: help; color: #6b7280;">
                                ⓘ
                            </span>'
                        ),
                    ])
                    ->columns(2)
                    ->helperText('Checklist ini hanya dapat diubah pada status Verifikasi.')
                    ->disabled(fn (?Aspirasi $record): bool =>
                        ! $record?->isVerifikasi()
                        || ! auth()->user()?->hasAnyRole([
                            'admin',
                            'super_admin',
                            'staf',
                        ])
                    ),
            ])



            ->visible(fn (?Aspirasi $record): bool =>
                $record?->isVerifikasi()
                || filled($record?->verification_checklist)
            )
            ->columnSpanFull();
    }
}