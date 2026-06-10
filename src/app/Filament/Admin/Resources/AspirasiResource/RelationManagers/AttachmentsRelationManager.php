<?php

namespace App\Filament\Admin\Resources\AspirasiResource\RelationManagers;

use App\Models\Aspirasi;
use App\Models\Attachment;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    protected static ?string $title = 'Lampiran Aspirasi';

    protected static ?string $modelLabel = 'Lampiran';

    protected static ?string $pluralModelLabel = 'Lampiran';

    private function getCurrentStage(): ?string
    {
        /** @var Aspirasi|null $aspirasi */
        $aspirasi = $this->getOwnerRecord();

        if (! $aspirasi) {
            return null;
        }

        if ($aspirasi->isMasuk() || $aspirasi->isVerifikasi()) {
            return Attachment::STAGE_AWAL;
        }

        if ($aspirasi->isTindakLanjut()) {
            return Attachment::STAGE_TINDAK_LANJUT;
        }

        return null;
    }

    private function canUploadAttachment(): bool
    {
        /** @var Aspirasi|null $aspirasi */
        $aspirasi = $this->getOwnerRecord();

        if (! $aspirasi) {
            return false;
        }

        if ($aspirasi->isMenungguPersetujuan() || $aspirasi->isFinalState()) {
            return false;
        }

        if ($aspirasi->isMasuk() || $aspirasi->isVerifikasi()) {
            return auth()->user()?->hasAnyRole([
                'admin',
                'super_admin',
                'staf',
            ]) ?? false;
        }

        if ($aspirasi->isTindakLanjut()) {
            return auth()->user()?->hasAnyRole([
                'admin',
                'super_admin',
                'staf',
                'tenaga_ahli',
            ]) ?? false;
        }

        return false;
    }

    private function getCategoryOptionsByCurrentStage(): array
    {
        return match ($this->getCurrentStage()) {
            Attachment::STAGE_AWAL => Attachment::initialCategoryOptions(),
            Attachment::STAGE_TINDAK_LANJUT => Attachment::followUpCategoryOptions(),
            default => [],
        };
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Upload Lampiran')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->visible(fn (): bool => $this->canUploadAttachment())
                    ->form([
                        Forms\Components\Select::make('attachment_category')
                            ->label('Jenis Lampiran')
                            ->options(fn (): array => $this->getCategoryOptionsByCurrentStage())
                            ->required()
                            ->searchable(),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Lampiran')
                            ->disk('public')
                            ->directory('aspirasi/lampiran')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Catatan Lampiran')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('Opsional. Contoh: Surat pengaduan asli dari konstituen.'),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        /** @var Aspirasi $aspirasi */
                        $aspirasi = $this->getOwnerRecord();

                        $filePath = $data['file_path'];

                        $data['aspirasi_id'] = $aspirasi->id;
                        $data['status_id'] = $aspirasi->status_id;
                        $data['uploaded_by'] = auth()->id();
                        $data['stage'] = $this->getCurrentStage();
                        $data['is_locked'] = false;

                        $data['original_name'] = basename($filePath);
                        $data['file_type'] = Storage::disk('public')->exists($filePath)
                            ? Storage::disk('public')->mimeType($filePath)
                            : pathinfo($filePath, PATHINFO_EXTENSION);

                        $data['file_size'] = Storage::disk('public')->exists($filePath)
                            ? Storage::disk('public')->size($filePath)
                            : null;

                        return $data;
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('stage')
                    ->label('Tahap')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        Attachment::STAGE_AWAL => 'File Awal',
                        Attachment::STAGE_TINDAK_LANJUT => 'File Tindak Lanjut',
                        default => '-',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        Attachment::STAGE_AWAL => 'gray',
                        Attachment::STAGE_TINDAK_LANJUT => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('attachment_category')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => Attachment::categoryOptions()[$state] ?? '-')
                    ->color(fn (?string $state): string => match ($state) {
                        Attachment::CATEGORY_SURAT_PENGADUAN => 'primary',
                        Attachment::CATEGORY_BUKTI_AWAL => 'gray',
                        Attachment::CATEGORY_FOTO_KEJADIAN => 'warning',
                        Attachment::CATEGORY_DOKUMEN_PENDUKUNG => 'info',

                        Attachment::CATEGORY_SURAT_KOORDINASI => 'primary',
                        Attachment::CATEGORY_LAPORAN => 'success',
                        Attachment::CATEGORY_HASIL_MEDIASI => 'warning',
                        Attachment::CATEGORY_DOKUMENTASI_LANJUTAN => 'info',

                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('original_name')
                    ->label('Nama File')
                    ->searchable()
                    ->limit(45)
                    ->tooltip(fn (Attachment $record): string => $record->original_name),

                Tables\Columns\TextColumn::make('file_type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(function (?string $state): string {
                        if (! $state) {
                            return '-';
                        }

                        return str_contains($state, '/')
                            ? strtoupper(str($state)->after('/')->toString())
                            : strtoupper($state);
                    })
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('file_size')
                    ->label('Ukuran')
                    ->formatStateUsing(function (?int $state): string {
                        if (! $state) {
                            return '-';
                        }

                        if ($state >= 1024 * 1024) {
                            return round($state / 1024 / 1024, 2) . ' MB';
                        }

                        return round($state / 1024, 2) . ' KB';
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('is_locked')
                    ->label('Status File')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Terkunci' : 'Dapat Diedit')
                    ->color(fn (bool $state): string => $state ? 'danger' : 'success')
                    ->icon(fn (bool $state): string => $state ? 'heroicon-o-lock-closed' : 'heroicon-o-lock-open'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Catatan')
                    ->limit(35)
                    ->placeholder('-')
                    ->tooltip(fn (Attachment $record): ?string => $record->description)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Diunggah Oleh')
                    ->badge()
                    ->color('info')
                    ->default('System'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Upload')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('stage')
                    ->label('Tahap File')
                    ->getTitleFromRecordUsing(fn (Attachment $record): string => match ($record->stage) {
                        Attachment::STAGE_AWAL => 'File Awal',
                        Attachment::STAGE_TINDAK_LANJUT => 'File Tindak Lanjut',
                        default => 'Lainnya',
                    })
                    ->collapsible(),
            ])
            ->defaultGroup('stage')
            ->filters([
                Tables\Filters\SelectFilter::make('stage')
                    ->label('Tahap File')
                    ->options(Attachment::stageOptions()),

                Tables\Filters\SelectFilter::make('attachment_category')
                    ->label('Jenis Lampiran')
                    ->options(Attachment::categoryOptions()),

                Tables\Filters\TernaryFilter::make('is_locked')
                    ->label('Status Lock')
                    ->placeholder('Semua File')
                    ->trueLabel('Terkunci')
                    ->falseLabel('Dapat Diedit'),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('Buka')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Attachment $record): string => Storage::disk('public')->url($record->file_path))
                    ->openUrlInNewTab(),

                // Tables\Actions\Action::make('download')
                //     ->label('Download')
                //     ->icon('heroicon-o-arrow-down-tray')
                //     ->url(fn (Attachment $record): string => Storage::disk('public')->url($record->file_path))
                //     ->openUrlInNewTab(),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->visible(function (Attachment $record): bool {
                        if ($record->isLocked()) {
                            return false;
                        }

                        $aspirasi = $record->aspirasi;

                        if (! $aspirasi) {
                            return false;
                        }

                        if ($aspirasi->isMenungguPersetujuan() || $aspirasi->isFinalState()) {
                            return false;
                        }

                        if ($record->isAwal()) {
                            return ($aspirasi->isMasuk() || $aspirasi->isVerifikasi())
                                && (auth()->user()?->hasAnyRole([
                                    'admin',
                                    'super_admin',
                                    'staf',
                                ]) ?? false);
                        }

                        if ($record->isTindakLanjut()) {
                            return $aspirasi->isTindakLanjut()
                                && (auth()->user()?->hasAnyRole([
                                    'admin',
                                    'super_admin',
                                    'staf',
                                    'tenaga_ahli',
                                ]) ?? false);
                        }

                        return false;
                    })
                    ->before(function (Attachment $record): void {
                        if ($record->file_path && Storage::disk('public')->exists($record->file_path)) {
                            Storage::disk('public')->delete($record->file_path);
                        }
                    }),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Belum ada lampiran')
            ->emptyStateDescription('Lampiran akan tampil berdasarkan File Awal dan File Tindak Lanjut.')
            ->emptyStateIcon('heroicon-o-paper-clip');
    }
}