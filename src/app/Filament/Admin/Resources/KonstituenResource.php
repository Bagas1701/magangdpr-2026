<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KonstituenResource\Pages;
use App\Models\Konstituen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class KonstituenResource extends Resource
{
    protected static ?string $model = Konstituen::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Konstituen';
    protected static ?string $modelLabel = 'Konstituen';
    protected static ?string $pluralModelLabel = 'Konstituen';
    protected static ?int $navigationSort = 2;

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
        return auth()->check() && auth()->user()->canAccessAspirasiModule();
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->canAccessAspirasiModule();
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->isAdminLevel();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('kontak')
                    ->label('Kontak')
                    ->tel()
                    ->maxLength(255),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('wilayah')
                    ->label('Wilayah')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kontak')
                    ->label('Kontak')
                    ->searchable(),

                Tables\Columns\TextColumn::make('wilayah')
                    ->label('Wilayah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Konstituen $record) => auth()->user()?->isAdminLevel()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->isAdminLevel()),
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
            'index' => Pages\ListKonstituens::route('/'),
            'create' => Pages\CreateKonstituen::route('/create'),
            'edit' => Pages\EditKonstituen::route('/{record}/edit'),
        ];
    }
}