<?php

namespace App\Filament\Admin\Resources\AspirasiResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StatusHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'statusHistories';

    protected static ?string $title = 'Riwayat Status';

    protected static ?string $modelLabel = 'Riwayat Status';

    protected static ?string $pluralModelLabel = 'Riwayat Status';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('new_status')
            ->columns([
                Tables\Columns\TextColumn::make('old_status')
                    ->label('Dari')
                    ->badge()
                    ->default('-'),

                Tables\Columns\TextColumn::make('new_status')
                    ->label('Ke')
                    ->badge(),

                Tables\Columns\TextColumn::make('changer.name')
                    ->label('Diubah Oleh')
                    ->default('System'),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(40),

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
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}