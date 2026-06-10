<?php

namespace App\Filament\Admin\Resources\KonstituenResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AspirasisRelationManager extends RelationManager
{
    protected static string $relationship = 'aspirasis';

    protected static ?string $title = 'Riwayat Aspirasi';

    protected static ?string $modelLabel = 'Aspirasi';

    protected static ?string $pluralModelLabel = 'Riwayat Aspirasi';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('judul')
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->weight('bold')
                    ->limit(35),

                Tables\Columns\TextColumn::make('kategoriAspirasi.nama')
                    ->label('Kategori')
                    ->badge()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status.nama')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Masuk' => 'gray',
                        'Verifikasi' => 'info',
                        'Tindak Lanjut' => 'warning',
                        'Menunggu Persetujuan' => 'primary',
                        'Selesai' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('prioritas')
                    ->label('Prioritas')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : '-')
                    ->color(fn (?string $state): string => match ($state) {
                        'rendah' => 'gray',
                        'sedang' => 'info',
                        'tinggi' => 'warning',
                        'mendesak' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tanggal_kejadian')
                    ->label('Tanggal Kejadian')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('lokasi_kejadian')
                    ->label('Lokasi')
                    ->limit(30)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_id')
                    ->label('Status')
                    ->relationship('status', 'nama')
                    ->preload()
                    ->searchable(),

                Tables\Filters\SelectFilter::make('prioritas')
                    ->label('Prioritas')
                    ->options([
                        'rendah' => 'Rendah',
                        'sedang' => 'Sedang',
                        'tinggi' => 'Tinggi',
                        'mendesak' => 'Mendesak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\EditAction::make()
                    ->label('Edit'),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum ada aspirasi')
            ->emptyStateDescription('Konstituen ini belum memiliki riwayat aspirasi.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}