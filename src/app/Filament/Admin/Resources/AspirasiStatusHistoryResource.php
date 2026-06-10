<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AspirasiStatusHistoryResource\Pages;
use App\Models\AspirasiStatusHistory;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AspirasiStatusHistoryResource extends Resource
{
    protected static ?string $model = AspirasiStatusHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Riwayat Status Aspirasi';
    protected static ?string $modelLabel = 'Riwayat Status Aspirasi';
    protected static ?string $pluralModelLabel = 'Riwayat Status Aspirasi';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?int $navigationSort = 99;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->canAccessAspirasiModule();
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->canAccessAspirasiModule();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No Riwayat')
                    ->sortable(),

                Tables\Columns\TextColumn::make('aspirasi.judul')
                    ->label('Aspirasi')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('old_status')
                    ->label('Dari')
                    ->badge()
                    ->default('-'),

                Tables\Columns\TextColumn::make('new_status')
                    ->label('Ke')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('changer.name')
                    ->label('Diubah Oleh')
                    ->default('System')
                    ->searchable(),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('new_status')
                    ->label('Status Baru')
                    ->options([
                        'Masuk' => 'Masuk',
                        'Verifikasi' => 'Verifikasi',
                        'Tindak Lanjut' => 'Tindak Lanjut',
                        'Selesai' => 'Selesai',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderByDesc('id');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAspirasiStatusHistories::route('/'),
        ];
    }
}