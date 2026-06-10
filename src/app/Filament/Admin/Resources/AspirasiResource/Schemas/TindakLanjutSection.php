<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Schemas;

use App\Models\Aspirasi;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;

class TindakLanjutSection
{
    public static function make(): Section
    {
        return Section::make('Proses Tindak Lanjut')
            ->description('Digunakan staf dan tenaga ahli untuk mencatat hasil proses, kajian, dan rekomendasi sebelum diajukan ke Anggota Dewan.')
            ->schema([
                Textarea::make('new_kajian_note')
                    ->label('Kajian / Rekomendasi Tindak Lanjut')
                    ->rows(5)
                    ->placeholder('Contoh: Berdasarkan dokumen yang diterima, aspirasi ini perlu ditindaklanjuti melalui koordinasi dengan pihak terkait...')
                    ->helperText('Diisi oleh Tenaga Ahli, Staf, Admin, atau Super Admin sebagai catatan tindak lanjut.')
                    ->visible(fn (?Aspirasi $record): bool =>
                        (bool) (
                            $record?->isTindakLanjut()
                            && auth()->user()?->hasAnyRole([
                                'admin',
                                'super_admin',
                                'staf',
                                'tenaga_ahli',
                            ])
                        )
                    )
                    ->columnSpanFull(),
            ])
            ->visible(fn (?Aspirasi $record): bool => (bool) $record?->isTindakLanjut())
            ->columnSpanFull();
    }
}