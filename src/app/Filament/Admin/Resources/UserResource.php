<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $modelLabel = 'User';
    protected static ?string $pluralModelLabel = 'Users';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check()
            && auth()->user()?->hasAnyRole(['super_admin', 'admin']);
    }

    public static function canViewAny(): bool
    {
        return auth()->check()
            && auth()->user()?->hasAnyRole(['super_admin', 'admin']);
    }

    public static function canCreate(): bool
    {
        return auth()->check()
            && auth()->user()?->hasAnyRole(['super_admin', 'admin']);
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        if ($user->hasRole('admin')) {
            if ($record->hasRole('super_admin')) {
                return false;
            }

            if ($record->hasRole('admin')) {
                return $record->id === $user->id;
            }

            return $record->hasAnyRole(['staf', 'tenaga_ahli']);
        }

        return false;
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        if (! $user || $record->id === $user->id) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return ! $record->hasRole('super_admin');
        }

        if ($user->hasRole('admin')) {
            return $record->hasAnyRole(['staf', 'tenaga_ahli']);
        }

        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole('super_admin')) {
            return $query;
        }

        if ($user->hasRole('admin')) {
            return $query->whereDoesntHave('roles', function (Builder $query) {
                $query->where('name', 'super_admin');
            });
        }

        return $query->whereRaw('1 = 0');
    }

    private static function roleOptions(): array
    {
        $user = auth()->user();

        if ($user?->hasRole('super_admin')) {
            return Role::whereIn('name', [
                'admin',
                'anggota_dewan',
                'anggota',
                'staf',
                'tenaga_ahli',
            ])
                ->orderBy('name')
                ->pluck('name', 'name')
                ->toArray();
        }

        if ($user?->hasRole('admin')) {
            return Role::whereIn('name', [
                'staf',
                'tenaga_ahli',
            ])
                ->orderBy('name')
                ->pluck('name', 'name')
                ->toArray();
        }

        return [];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
                
            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->revealable()
                ->required(fn (string $operation): bool => $operation === 'create')
                ->dehydrated(fn ($state) => filled($state))
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->maxLength(255)
                ->helperText('Kosongkan saat edit jika password tidak diubah.'),

            Forms\Components\Select::make('role_name')
                ->label('Role')
                ->options(fn () => self::roleOptions())
                ->required()
                ->searchable()
                ->preload()
                ->default(fn (?User $record) => $record?->roles->first()?->name)
                ->disabled(function (?User $record): bool {
                    $user = auth()->user();

                    return $user?->hasRole('admin')
                        && $record?->hasRole('admin');
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->separator(', '),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (User $record): bool => self::canEdit($record)),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $record): bool => self::canDelete($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->hasRole('super_admin') ?? false),
                ]),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}