<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KategoriAspirasiResource\Pages;
use App\Models\KategoriAspirasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriAspirasiResource extends Resource
{
    protected static ?string $model = KategoriAspirasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Kategori Aspirasi';
    protected static ?string $modelLabel = 'Kategori Aspirasi';
    protected static ?string $pluralModelLabel = 'Kategori Aspirasi';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->canManageMasterData();
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->canManageMasterData();
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->canManageMasterData();
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->canManageMasterData();
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->canManageMasterData();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->nullable(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable(),

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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoriAspirasis::route('/'),
            'create' => Pages\CreateKategoriAspirasi::route('/create'),
            'edit' => Pages\EditKategoriAspirasi::route('/{record}/edit'),
        ];
    }
}