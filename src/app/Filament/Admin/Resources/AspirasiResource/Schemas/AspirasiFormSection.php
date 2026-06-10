<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Schemas;

use App\Models\Aspirasi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class AspirasiFormSection
{
    private static function isAnggotaDewan(): bool
    {
        return auth()->user()?->hasAnyRole([
            'anggota_dewan',
        ]) ?? false;
    }

    private static function isFinalState(?Aspirasi $record): bool
    {
        return $record?->isFinalState() ?? false;
    }

    public static function make(): Section
    {
        return Section::make('Data Aspirasi')
            ->description('Informasi utama aspirasi yang dicatat dari konstituen.')
            ->schema([
                Select::make('konstituen_id')
                    ->label('Konstituen')
                    ->relationship('konstituen', 'nama')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn (?Aspirasi $record): bool =>
                        self::isAnggotaDewan() || self::isFinalState($record)
                    ),

                Select::make('kategori_aspirasi_id')
                    ->label('Kategori')
                    ->relationship('kategoriAspirasi', 'nama')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn (?Aspirasi $record): bool =>
                        self::isAnggotaDewan() || self::isFinalState($record)
                    ),

                TextInput::make('judul')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn (?Aspirasi $record): bool =>
                        self::isAnggotaDewan() || self::isFinalState($record)
                    ),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull()
                    ->disabled(fn (?Aspirasi $record): bool =>
                        self::isAnggotaDewan() || self::isFinalState($record)
                    ),

                DatePicker::make('tanggal_kejadian')
                    ->label('Tanggal Kejadian')
                    ->disabled(fn (?Aspirasi $record): bool =>
                        self::isAnggotaDewan() || self::isFinalState($record)
                    ),

                TextInput::make('lokasi_kejadian')
                    ->label('Lokasi Kejadian')
                    ->maxLength(255)
                    ->disabled(fn (?Aspirasi $record): bool =>
                        self::isAnggotaDewan() || self::isFinalState($record)
                    ),

                Select::make('prioritas')
                    ->label('Prioritas')
                    ->options([
                        'rendah' => 'Rendah',
                        'sedang' => 'Sedang',
                        'tinggi' => 'Tinggi',
                        'mendesak' => 'Mendesak',
                    ])
                    ->default('sedang')
                    ->required()
                    ->disabled(fn (?Aspirasi $record): bool =>
                        self::isAnggotaDewan() || self::isFinalState($record)
                    ),
            ])
            ->columns(2);
    }
}