<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WebsiteImageResource\Pages;
use App\Models\WebsiteImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebsiteImageResource extends Resource
{
    protected static ?string $model = WebsiteImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Manajemen Website';

    protected static ?string $navigationLabel = 'Gambar Website';

    protected static ?string $modelLabel = 'Gambar Website';

    protected static ?string $pluralModelLabel = 'Gambar Website';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Gambar')
                    ->placeholder('Contoh: hero_photo')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Gunakan nama unik, misalnya: site_logo, hero_photo, profile_photo.'),

                Forms\Components\FileUpload::make('image')
                    ->label('Upload Gambar')
                    ->image()
                    ->disk('public')
                    ->directory('website-images')
                    ->imageEditor()
                    ->maxSize(4096)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->square()
                    ->height(70),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Gambar')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()?->hasRole('super_admin')),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsiteImages::route('/'),
            'create' => Pages\CreateWebsiteImage::route('/create'),
            'edit' => Pages\EditWebsiteImage::route('/{record}/edit'),
        ];
    }
}