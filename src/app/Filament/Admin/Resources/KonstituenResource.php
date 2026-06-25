<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KonstituenResource\Pages;
use App\Filament\Admin\Resources\KonstituenResource\RelationManagers;
use App\Models\Konstituen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KonstituenResource extends Resource
{
    protected static ?string $navigationGroup = 'Operasional';
    
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

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->canAccessAspirasiModule();
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->canAccessAspirasiModule();
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check()
        && auth()->user()->canAccessAspirasiModule()
        && ! auth()->user()->hasAnyRole(['anggota_dewan']);
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->isAdminLevel();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('aspirasis')
            ->with([
                'latestAspirasi.status',
            ]);
    }

    private static function wilayahOptions(): array
    {
        return collect(config('wilayah.sumut_iii'))
            ->keys()
            ->mapWithKeys(fn (string $wilayah): array => [$wilayah => $wilayah])
            ->toArray();
    }

    private static function kecamatanOptions(?string $kabupatenKota): array
    {
        if (! $kabupatenKota) {
            return [];
        }

        return collect(config("wilayah.sumut_iii.{$kabupatenKota}", []))
            ->mapWithKeys(fn (string $kecamatan): array => [$kecamatan => $kecamatan])
            ->toArray();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Identitas Konstituen')
                    ->description('Data utama masyarakat atau konstituen yang menyampaikan aspirasi.')
                    ->schema([
                        Forms\Components\FileUpload::make('foto_ktp')
                            ->label('Foto KTP')
                            ->image()
                            ->directory('konstituen/ktp')
                            ->imagePreviewHeight('180')
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->maxLength(16)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('kontak')
                            ->label('Kontak')
                            ->tel()
                            ->maxLength(30),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Alamat dan Wilayah')
                    ->description('Wilayah konstituen berdasarkan Dapil Sumatera Utara III.')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('kabupaten_kota')
                            ->label('Kabupaten/Kota')
                            ->options(fn (): array => self::wilayahOptions())
                            ->searchable()
                            ->live()
                            ->required()
                            ->afterStateUpdated(fn (Set $set): mixed => $set('kecamatan', null)),

                        Forms\Components\Select::make('kecamatan')
                            ->label('Kecamatan')
                            ->options(fn (Get $get): array => self::kecamatanOptions($get('kabupaten_kota')))
                            ->searchable()
                            ->required()
                            ->disabled(fn (Get $get): bool => blank($get('kabupaten_kota'))),

                        Forms\Components\TextInput::make('kelurahan')
                            ->label('Kelurahan/Desa')
                            ->maxLength(255),
                    ])
                    ->columns(3),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Profil Konstituen')
                    ->description('Informasi identitas dan wilayah konstituen.')
                    ->schema([
                        Infolists\Components\ImageEntry::make('foto_ktp')
                            ->label('Foto KTP')
                            ->height(160)
                            ->square()
                            ->placeholder('Belum ada foto KTP'),

                        Infolists\Components\TextEntry::make('nama')
                            ->label('Nama Lengkap')
                            ->weight('bold')
                            ->size('lg'),

                        Infolists\Components\TextEntry::make('nik')
                            ->label('NIK')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('kontak')
                            ->label('Kontak')
                            ->copyable()
                            ->placeholder('-'),

                        Infolists\Components\TextEntry::make('alamat')
                            ->label('Alamat')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('kabupaten_kota')
                            ->label('Kabupaten/Kota')
                            ->badge()
                            ->color('info')
                            ->placeholder('-'),

                        Infolists\Components\TextEntry::make('kecamatan')
                            ->label('Kecamatan')
                            ->placeholder('-'),

                        Infolists\Components\TextEntry::make('kelurahan')
                            ->label('Kelurahan/Desa')
                            ->placeholder('-'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Ringkasan Aspirasi')
                    ->description('Statistik singkat aktivitas aspirasi konstituen.')
                    ->schema([
                        Infolists\Components\TextEntry::make('aspirasis_count')
                            ->label('Jumlah Aspirasi')
                            ->badge()
                            ->color(fn (?int $state): string => match (true) {
                                ($state ?? 0) === 0 => 'gray',
                                ($state ?? 0) <= 2 => 'success',
                                default => 'warning',
                            }),

                        Infolists\Components\TextEntry::make('latestAspirasi.status.nama')
                            ->label('Status Terakhir')
                            ->badge()
                            ->placeholder('Belum Ada Aspirasi')
                            ->color(fn (?string $state): string => match ($state) {
                                'Masuk' => 'gray',
                                'Verifikasi' => 'info',
                                'Tindak Lanjut' => 'warning',
                                'Menunggu Persetujuan' => 'primary',
                                'Selesai' => 'success',
                                'Ditolak' => 'danger',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('badge_status')
                            ->label('Kategori Konstituen')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Belum Ada Aspirasi' => 'gray',
                                'Aktif' => 'success',
                                'Sering Melapor' => 'danger',
                                default => 'gray',
                            }),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->url(fn (Konstituen $record): string => self::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(false),

                Tables\Columns\TextColumn::make('kontak')
                    ->label('Kontak')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('kabupaten_kota')
                    ->label('Wilayah')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('aspirasis_count')
                    ->label('Jumlah Aspirasi')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'gray',
                        $state <= 2 => 'success',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('latestAspirasi.status.nama')
                    ->label('Status Terakhir')
                    ->badge()
                    ->placeholder('Belum Ada Aspirasi')
                    ->color(fn (?string $state): string => match ($state) {
                        'Masuk' => 'gray',
                        'Verifikasi' => 'info',
                        'Tindak Lanjut' => 'warning',
                        'Menunggu Persetujuan' => 'primary',
                        'Selesai' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('badge_status')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Belum Ada Aspirasi' => 'gray',
                        'Aktif' => 'success',
                        'Sering Melapor' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kabupaten_kota')
                    ->label('Wilayah')
                    ->options(fn (): array => self::wilayahOptions())
                    ->searchable(),

                Tables\Filters\SelectFilter::make('status_aspirasi')
                    ->label('Status Aspirasi')
                    ->options(fn (): array => \App\Models\StatusAspirasi::query()
                        ->pluck('nama', 'id')
                        ->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, $statusId): Builder => $query->whereHas(
                                'aspirasis',
                                fn (Builder $query): Builder => $query->where('status_id', $statusId)
                            )
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye'),

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Konstituen $record): bool => auth()->user()?->isAdminLevel() ?? false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->isAdminLevel() ?? false),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum ada data konstituen')
            ->emptyStateDescription('Tambahkan data konstituen terlebih dahulu sebelum membuat aspirasi.')
            ->emptyStateIcon('heroicon-o-user-group');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AspirasisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKonstituens::route('/'),
            'create' => Pages\CreateKonstituen::route('/create'),
            'view' => Pages\ViewKonstituen::route('/{record}'),
            'edit' => Pages\EditKonstituen::route('/{record}/edit'),
        ];
    }
}