<?php

namespace App\Filament\Admin\Resources\AspirasiResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $title = 'Daftar Kajian / Rekomendasi';

    protected static ?string $modelLabel = 'Kajian / Rekomendasi';

    protected static ?string $pluralModelLabel = 'Daftar Kajian / Rekomendasi';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('catatan')
            ->columns([
                Tables\Columns\TextColumn::make('catatan')
                    ->label('Isi Kajian / Rekomendasi')
                    ->wrap()
                    ->searchable()
                    ->limit(180)
                    ->tooltip(fn ($record): ?string => $record->catatan),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Ditulis Oleh')
                    ->badge()
                    ->color('info')
                    ->placeholder('System'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole([
                        'admin',
                        'super_admin',
                    ]) ?? false),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum ada kajian / rekomendasi')
            ->emptyStateDescription('Catatan tindak lanjut yang disimpan akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}