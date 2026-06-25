<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StatusAspirasiResource\Pages;
use App\Models\StatusAspirasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StatusAspirasiResource extends Resource
{
    protected static ?string $model = StatusAspirasi::class;

    protected static ?string $navigationGroup = 'Operasional';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Status Aspirasi';
    protected static ?string $modelLabel = 'Status Aspirasi';
    protected static ?string $pluralModelLabel = 'Status Aspirasi';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole([
            'admin',
            'super_admin',
            'staf',
            'tenaga_ahli',
            'anggota_dewan',
        ]) ?? false;
    }

    public static function canView($record): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Status')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->disabledOn('edit'),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3),

                Forms\Components\TextInput::make('urutan')
                    ->label('Urutan')
                    ->numeric()
                    ->required()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('urutan')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Status')
                    ->searchable()
                    ->sortable()
                    ->url(fn (StatusAspirasi $record) => self::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(false),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(40),

                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye'),

                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->visible(fn (): bool => auth()->user()?->hasRole('super_admin') ?? false),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatusAspirasis::route('/'),
            'view' => Pages\ViewStatusAspirasi::route('/{record}'),
            'edit' => Pages\EditStatusAspirasi::route('/{record}/edit'),
        ];
    }
}