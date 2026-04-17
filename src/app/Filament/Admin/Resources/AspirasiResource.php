<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AspirasiResource\Pages;
use App\Filament\Admin\Resources\AspirasiResource\RelationManagers\StatusHistoriesRelationManager;
use App\Models\Aspirasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class AspirasiResource extends Resource
{
    protected static ?string $model = Aspirasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Aspirasi';
    protected static ?string $modelLabel = 'Aspirasi';
    protected static ?string $pluralModelLabel = 'Aspirasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('status_summary')
                    ->label('Ringkasan Status')
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->content(function (?Aspirasi $record) {
                        if (! $record) {
                            return '-';
                        }

                        $latestHistory = $record->statusHistories()->first();

                        if (! $latestHistory) {
                            return 'Belum ada riwayat status.';
                        }

                        $changedBy = $latestHistory->changer?->name ?? 'System';
                        $changedAt = $latestHistory->created_at?->format('d M Y H:i') ?? '-';
                        $note = $latestHistory->catatan ?: '-';

                        return new HtmlString("
                            <div style='line-height: 1.8'>
                                <div><strong>Status Saat Ini:</strong> {$record->status}</div>
                                <div><strong>Perubahan Terakhir:</strong> {$changedBy}</div>
                                <div><strong>Waktu:</strong> {$changedAt}</div>
                                <div><strong>Catatan Terakhir:</strong> {$note}</div>
                            </div>
                        ");
                    })
                    ->columnSpanFull(),

                Forms\Components\Select::make('konstituen_id')
                    ->relationship('konstituen', 'nama')
                    ->label('Konstituen')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('kategori_aspirasi_id')
                    ->relationship('kategoriAspirasi', 'nama')
                    ->label('Kategori')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->rows(4),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(function (string $operation, ?Aspirasi $record) {
                        $user = auth()->user();

                        if ($operation === 'create' || ! $record) {
                            return [
                                'Masuk' => 'Masuk',
                            ];
                        }

                        $currentStatus = $record->status;
                        $isPrivileged = $user?->hasAnyRole(['super_admin', 'admin', 'anggota']);
                        $isOperational = $user?->hasAnyRole(['tenaga_ahli', 'staf']);

                        if ($isPrivileged) {
                            return match ($currentStatus) {
                                'Masuk' => [
                                    'Masuk' => 'Masuk',
                                    'Verifikasi' => 'Verifikasi',
                                ],
                                'Verifikasi' => [
                                    'Verifikasi' => 'Verifikasi',
                                    'Tindak Lanjut' => 'Tindak Lanjut',
                                ],
                                'Tindak Lanjut' => [
                                    'Tindak Lanjut' => 'Tindak Lanjut',
                                    'Selesai' => 'Selesai',
                                ],
                                'Selesai' => [
                                    'Selesai' => 'Selesai',
                                ],
                                default => [
                                    $currentStatus => $currentStatus,
                                ],
                            };
                        }

                        if ($isOperational) {
                            return match ($currentStatus) {
                                'Masuk' => [
                                    'Masuk' => 'Masuk',
                                    'Verifikasi' => 'Verifikasi',
                                ],
                                'Verifikasi' => [
                                    'Verifikasi' => 'Verifikasi',
                                    'Tindak Lanjut' => 'Tindak Lanjut',
                                ],
                                'Tindak Lanjut' => [
                                    'Tindak Lanjut' => 'Tindak Lanjut',
                                ],
                                'Selesai' => [
                                    'Selesai' => 'Selesai',
                                ],
                                default => [
                                    $currentStatus => $currentStatus,
                                ],
                            };
                        }

                        return [
                            $currentStatus => $currentStatus,
                        ];
                    })
                    ->default('Masuk')
                    ->disabled(fn (string $operation): bool => $operation === 'edit' && ! auth()->user()?->hasAnyRole([
                        'super_admin',
                        'admin',
                        'anggota',
                        'tenaga_ahli',
                        'staf',
                    ]))
                    ->required(),

                Forms\Components\Textarea::make('status_change_note')
                    ->label('Catatan Perubahan Status')
                    ->rows(3)
                    ->helperText('Wajib diisi jika status diubah saat edit data.')
                    ->visible(fn (string $operation): bool => $operation === 'edit' && auth()->user()?->hasAnyRole([
                        'super_admin',
                        'admin',
                        'anggota',
                        'tenaga_ahli',
                        'staf',
                    ])),

                Forms\Components\Select::make('approval_status')
                    ->label('Approval Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'admin', 'anggota']))
                    ->helperText('Bisa diubah manual atau melalui tombol Approve / Reject di halaman edit.')
                    ->disabled(),

                Forms\Components\Textarea::make('approval_note')
                    ->label('Catatan Approval')
                    ->rows(3)
                    ->helperText('Wajib diisi saat approve atau reject.')
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['super_admin', 'admin', 'anggota'])),

                Forms\Components\Placeholder::make('approval_summary')
                    ->label('Ringkasan Approval')
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->content(function (?Aspirasi $record) {
                        if (! $record) {
                            return '-';
                        }

                        $approvalStatus = $record->approval_status ?? 'pending';
                        $approver = $record->approver?->name ?? '-';
                        $approvedAt = $record->approved_at?->format('d M Y H:i') ?? '-';
                        $approvalNote = $record->approval_note ?: '-';

                        return new HtmlString("
                            <div style='line-height: 1.8'>
                                <div><strong>Status Approval:</strong> {$approvalStatus}</div>
                                <div><strong>Disetujui / Diputus oleh:</strong> {$approver}</div>
                                <div><strong>Waktu Approval:</strong> {$approvedAt}</div>
                                <div><strong>Catatan Approval:</strong> {$approvalNote}</div>
                            </div>
                        ");
                    })
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('file_bukti')
                    ->label('File Bukti')
                    ->directory('bukti')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('konstituen.nama')
                    ->label('Nama Konstituen')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategoriAspirasi.nama')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Masuk' => 'gray',
                        'Verifikasi' => 'warning',
                        'Tindak Lanjut' => 'info',
                        'Selesai' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('approval_status')
                    ->label('Approval')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('approver.name')
                    ->label('Approver')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Approved At')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori_aspirasi_id')
                    ->label('Kategori')
                    ->relationship('kategoriAspirasi', 'nama'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Masuk' => 'Masuk',
                        'Verifikasi' => 'Verifikasi',
                        'Tindak Lanjut' => 'Tindak Lanjut',
                        'Selesai' => 'Selesai',
                    ]),

                Tables\Filters\SelectFilter::make('approval_status')
                    ->label('Approval')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasAnyRole([
                        'super_admin',
                        'admin',
                        'anggota',
                        'tenaga_ahli',
                        'staf',
                    ])),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasAnyRole([
                        'super_admin',
                        'admin',
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasAnyRole([
                            'super_admin',
                            'admin',
                        ])),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StatusHistoriesRelationManager::class,
        ];
    }

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAspirasis::route('/'),
            'create' => Pages\CreateAspirasi::route('/create'),
            'edit' => Pages\EditAspirasi::route('/{record}/edit'),
        ];
    }
}