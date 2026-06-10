<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AspirasiResource\Pages;
use App\Filament\Admin\Resources\AspirasiResource\RelationManagers;
use App\Filament\Admin\Resources\AspirasiResource\Schemas\ApprovalSection;
use App\Filament\Admin\Resources\AspirasiResource\Schemas\AspirasiFormSection;
use App\Filament\Admin\Resources\AspirasiResource\Schemas\TindakLanjutSection;
use App\Filament\Admin\Resources\AspirasiResource\Schemas\VerificationChecklistSection;
use App\Models\Aspirasi;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AspirasiResource extends Resource
{
    protected static ?string $model = Aspirasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Aspirasi';

    protected static ?string $modelLabel = 'Aspirasi';

    protected static ?string $pluralModelLabel = 'Aspirasi';

    protected static ?int $navigationSort = 1;

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
        return auth()->check()
            && auth()->user()->canAccessAspirasiModule()
            && ! auth()->user()->hasAnyRole(['anggota', 'anggota_dewan']);
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->canAccessAspirasiModule();
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->isAdminLevel();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with([
                'konstituen',
                'kategoriAspirasi',
                'status',
                'creator',
            ]);

        if (auth()->user()?->hasAnyRole(['anggota', 'anggota_dewan'])) {
            return $query->whereHas('status', function (Builder $query): void {
                $query->whereIn('nama', [
                    Aspirasi::STATUS_MENUNGGU_PERSETUJUAN,
                    Aspirasi::STATUS_SELESAI,
                    Aspirasi::STATUS_DITOLAK,
                ]);
            });
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                AspirasiFormSection::make(),

                VerificationChecklistSection::make(),

                TindakLanjutSection::make(),

                ApprovalSection::make(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),
                    
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),

                Tables\Columns\TextColumn::make('konstituen.nama')
                    ->label('Konstituen')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('kategoriAspirasi.nama')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status.nama')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(fn (?string $state): string => match ($state) {
                        Aspirasi::STATUS_MASUK => 'gray',
                        Aspirasi::STATUS_VERIFIKASI => 'info',
                        Aspirasi::STATUS_TINDAK_LANJUT => 'warning',
                        Aspirasi::STATUS_MENUNGGU_PERSETUJUAN => 'primary',
                        Aspirasi::STATUS_SELESAI => 'success',
                        Aspirasi::STATUS_DITOLAK => 'danger',
                        default => 'gray',
                    })
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('approval_status')
                    ->label('Approval')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'revision' => 'Revisi',
                        'pending' => 'Pending',
                        default => 'Pending',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'revision' => 'warning',
                        'pending' => 'gray',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('prioritas')
                    ->label('Prioritas')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : '-')
                    ->color(fn (?string $state): string => match ($state) {
                        'rendah' => 'gray',
                        'sedang' => 'info',
                        'tinggi' => 'warning',
                        'mendesak' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tanggal_kejadian')
                    ->label('Tanggal Kejadian')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('lokasi_kejadian')
                    ->label('Lokasi')
                    ->searchable()
                    ->limit(30)
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_id')
                    ->label('Status')
                    ->relationship('status', 'nama')
                    ->preload()
                    ->searchable(),

                Tables\Filters\SelectFilter::make('kategori_aspirasi_id')
                    ->label('Kategori')
                    ->relationship('kategoriAspirasi', 'nama')
                    ->preload()
                    ->searchable(),

                Tables\Filters\SelectFilter::make('prioritas')
                    ->label('Prioritas')
                    ->options([
                        'rendah' => 'Rendah',
                        'sedang' => 'Sedang',
                        'tinggi' => 'Tinggi',
                        'mendesak' => 'Mendesak',
                    ]),

                Tables\Filters\SelectFilter::make('approval_status')
                    ->label('Approval')
                    ->options([
                        'pending' => 'Pending',
                        'revision' => 'Revisi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\EditAction::make()
                    ->label(fn (): string =>
                        auth()->user()?->hasAnyRole(['anggota', 'anggota_dewan'])
                            ? 'Review'
                            : 'Edit'
                    ),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Aspirasi $record): bool => auth()->user()?->isAdminLevel() ?? false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->isAdminLevel() ?? false),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum ada data aspirasi')
            ->emptyStateDescription('Data aspirasi akan tampil sesuai hak akses role pengguna.')
            ->emptyStateIcon('heroicon-o-document-text');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttachmentsRelationManager::class,
            RelationManagers\StatusHistoriesRelationManager::class,
            RelationManagers\NotesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAspirasis::route('/'),
            'create' => Pages\CreateAspirasi::route('/create'),
            'view' => Pages\ViewAspirasi::route('/{record}'),
            'edit' => Pages\EditAspirasi::route('/{record}/edit'),
        ];
    }
}