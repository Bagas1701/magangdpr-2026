<?php

namespace App\Filament\Admin\Widgets;

use App\Models\AspirasiStatusHistory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentAspirasiActivity extends BaseWidget
{
    protected static ?string $heading = 'Aktivitas Aspirasi Terbaru';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AspirasiStatusHistory::query()
                    ->with(['aspirasi', 'changer'])
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('aspirasi.ticket_number')
                    ->label('No. Tiket')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('aspirasi.judul')
                    ->label('Aspirasi')
                    ->limit(45)
                    ->searchable(),

                Tables\Columns\TextColumn::make('old_status')
                    ->label('Dari')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('new_status')
                    ->label('Ke')
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

                Tables\Columns\TextColumn::make('changer.name')
                    ->label('Oleh')
                    ->placeholder('System'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}