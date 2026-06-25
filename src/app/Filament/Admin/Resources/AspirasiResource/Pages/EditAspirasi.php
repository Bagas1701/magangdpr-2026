<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Pages;

use App\Filament\Admin\Resources\AspirasiResource;
use App\Models\Aspirasi;
use App\Models\AspirasiNote;
use App\Models\Attachment;
use App\Services\AspirasiNotificationService;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAspirasi extends EditRecord
{
    protected static string $resource = AspirasiResource::class;

    protected ?string $newKajianNote = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mulaiVerifikasi')
                ->label('Mulai Verifikasi')
                ->icon('heroicon-o-check-circle')
                ->color('info')
                ->visible(fn (): bool => $this->record->isMasuk() && $this->canProcessByStaff())
                ->requiresConfirmation()
                ->modalHeading('Mulai Verifikasi Aspirasi')
                ->modalDescription('Status aspirasi akan berubah dari Masuk menjadi Verifikasi.')
                ->action(function (): void {
                    $this->changeStatus(
                        Aspirasi::STATUS_VERIFIKASI,
                        'Aspirasi mulai diverifikasi oleh staf.'
                    );
                }),

            Actions\Action::make('verifikasiLanjutkan')
                ->label('Verifikasi & Lanjutkan')
                ->icon('heroicon-o-arrow-right-circle')
                ->color('warning')
                ->visible(fn (): bool => $this->record->isVerifikasi() && $this->canProcessByStaff())
                ->requiresConfirmation()
                ->modalHeading('Lanjut ke Tindak Lanjut')
                ->modalDescription('Status aspirasi akan berubah menjadi Tindak Lanjut dan file tahap awal akan dikunci.')
                ->action(function (): void {
                    if (! $this->hasInitialAttachment()) {
                        Notification::make()
                            ->danger()
                            ->title('Lampiran awal belum tersedia')
                            ->body('Minimal harus ada satu lampiran tahap awal sebelum lanjut ke Tindak Lanjut.')
                            ->send();

                        return;
                    }

                    $this->lockInitialAttachments();

                    AspirasiNotificationService::menungguTindakLanjut($this->record);

                    $this->changeStatus(
                        Aspirasi::STATUS_TINDAK_LANJUT,
                        'Verifikasi selesai. File awal dikunci dan aspirasi masuk tahap tindak lanjut.'
                    );
                }),

            Actions\Action::make('ajukanPersetujuan')
                ->label('Ajukan Persetujuan')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->visible(fn (): bool => $this->record->isTindakLanjut() && $this->canProcessByStaff())
                ->requiresConfirmation()
                ->modalHeading('Ajukan ke Anggota Dewan')
                ->modalDescription('Status aspirasi akan berubah menjadi Menunggu Persetujuan.')
                ->action(function (): void {
                    AspirasiNotificationService::menungguPersetujuan($this->record);

                    $this->changeStatus(
                        Aspirasi::STATUS_MENUNGGU_PERSETUJUAN,
                        'Proses tindak lanjut selesai dan diajukan kepada Anggota Dewan.'
                    );
                }),

            Actions\Action::make('approve')
                ->label('Approve & Selesaikan')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn (): bool => $this->record->isMenungguPersetujuan() && $this->canApprove())
                ->form([
                    TextInput::make('nomor_disposisi')
                        ->label('Nomor Disposisi')
                        ->placeholder('Contoh: DISP/KOMIII/2026/001')
                        ->maxLength(255),

                    Select::make('jenis_keputusan')
                        ->label('Jenis Keputusan')
                        ->options([
                            'disetujui_selesai' => 'Disetujui & Selesai',
                            'disetujui_arsip' => 'Disetujui untuk Arsip',
                        ])
                        ->default('disetujui_selesai')
                        ->required(),
                ])
                ->modalHeading('Setujui dan Selesaikan Aspirasi')
                ->modalDescription('Aspirasi akan ditandai selesai dan menjadi read-only secara workflow.')
                ->action(function (array $data): void {
                    $this->record->update([
                        'approval_status' => 'approved',
                        'approval_note' => null,
                        'nomor_disposisi' => $data['nomor_disposisi'] ?? null,
                        'jenis_keputusan' => $data['jenis_keputusan'],
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    $this->lockAllAttachments();

                    AspirasiNotificationService::keputusanDewan($this->record, 'disetujui dan dinyatakan selesai');

                    $this->changeStatus(
                        Aspirasi::STATUS_SELESAI,
                        'Aspirasi disetujui oleh Anggota Dewan dan dinyatakan selesai.'
                    );
                }),

            Actions\Action::make('requestRevision')
                ->label('Minta Revisi')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->visible(fn (): bool => $this->record->isMenungguPersetujuan() && $this->canApprove())
                ->form([
                    TextInput::make('nomor_disposisi')
                        ->label('Nomor Disposisi')
                        ->placeholder('Contoh: DISP/KOMIII/2026/001')
                        ->maxLength(255),

                    Select::make('jenis_keputusan')
                        ->label('Jenis Keputusan')
                        ->options([
                            'revisi_data' => 'Revisi Data',
                            'revisi_dokumen' => 'Revisi Dokumen',
                            'revisi_tindak_lanjut' => 'Revisi Tindak Lanjut',
                        ])
                        ->default('revisi_tindak_lanjut')
                        ->required(),

                    Textarea::make('approval_note')
                        ->label('Catatan Revisi')
                        ->required()
                        ->rows(4),
                ])
                ->modalHeading('Minta Revisi Tindak Lanjut')
                ->modalDescription('Aspirasi akan dikembalikan ke tahap Tindak Lanjut untuk diperbaiki staf atau tenaga ahli.')
                ->action(function (array $data): void {
                    $this->record->update([
                        'approval_status' => 'revision',
                        'approval_note' => $data['approval_note'],
                        'nomor_disposisi' => $data['nomor_disposisi'] ?? null,
                        'jenis_keputusan' => $data['jenis_keputusan'],
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    $this->record->attachments()
                        ->where('stage', Attachment::STAGE_TINDAK_LANJUT)
                        ->update([
                            'is_locked' => false,
                        ]);

                    $this->record->changeStatus(
                        Aspirasi::STATUS_TINDAK_LANJUT,
                        'Anggota Dewan meminta revisi. Catatan: ' . $data['approval_note']
                    );

                    AspirasiNotificationService::keputusanDewan($this->record, 'diminta revisi');

                    Notification::make()
                        ->success()
                        ->title('Revisi berhasil diajukan')
                        ->body('Aspirasi dikembalikan ke tahap Tindak Lanjut untuk diperbaiki staf atau tenaga ahli.')
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (): bool => $this->record->isMenungguPersetujuan() && $this->canApprove())
                ->form([
                    TextInput::make('nomor_disposisi')
                        ->label('Nomor Disposisi')
                        ->placeholder('Contoh: DISP/KOMIII/2026/001')
                        ->maxLength(255),

                    Select::make('jenis_keputusan')
                        ->label('Jenis Keputusan')
                        ->options([
                            'ditolak_data_tidak_valid' => 'Ditolak - Data Tidak Valid',
                            'ditolak_bukan_kewenangan' => 'Ditolak - Bukan Kewenangan',
                            'ditolak_bukti_tidak_cukup' => 'Ditolak - Bukti Tidak Cukup',
                            'ditolak_duplikasi' => 'Ditolak - Duplikasi Aspirasi',
                        ])
                        ->default('ditolak_data_tidak_valid')
                        ->required(),

                    Textarea::make('approval_note')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->rows(4),
                ])
                ->modalHeading('Tolak Aspirasi')
                ->modalDescription('Aspirasi akan ditolak dan dikunci sebagai status final.')
                ->action(function (array $data): void {
                    $this->record->update([
                        'approval_status' => 'rejected',
                        'approval_note' => $data['approval_note'],
                        'nomor_disposisi' => $data['nomor_disposisi'] ?? null,
                        'jenis_keputusan' => $data['jenis_keputusan'],
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    $this->lockAllAttachments();

                    AspirasiNotificationService::keputusanDewan($this->record, 'ditolak');

                    $this->changeStatus(
                        Aspirasi::STATUS_DITOLAK,
                        'Aspirasi ditolak oleh Anggota Dewan. Alasan: ' . $data['approval_note']
                    );
                }),

            Actions\DeleteAction::make()
            ->before(function (): void {
                $judul = $this->record->judul;
                $nomor = $this->record->nomor_tiket ?? '-';

                AspirasiNotificationService::aspirasiDihapusByData($judul, $nomor);
            })
            ->visible(fn (): bool => auth()->user()?->hasAnyRole([
                'admin',
                'super_admin',
            ]) ?? false),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->newKajianNote = trim($data['new_kajian_note'] ?? '');

        unset(
            $data['attachment_file'],
            $data['new_kajian_note'],
            $data['status_change_note']
        );

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->newKajianNote !== '') {
            AspirasiNote::create([
                'aspirasi_id' => $this->record->id,
                'user_id' => auth()->id(),
                'catatan' => $this->newKajianNote,
            ]);
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()
                ->label('Kembali ke Daftar')
                ->url($this->getResource()::getUrl('index')),

            $this->getSaveFormAction()
                ->label('Simpan')
                ->visible(fn (): bool => ! $this->record->isFinalState() && ! $this->isAnggotaDewan()),
        ];
    }

    private function changeStatus(string $statusName, string $note): void
    {
        $this->record->changeStatus($statusName, $note);
        $this->record->refresh();

        Notification::make()
            ->success()
            ->title('Status aspirasi berhasil diperbarui')
            ->body("Status berubah menjadi {$statusName}.")
            ->send();

        $this->redirect($this->getResource()::getUrl('edit', [
            'record' => $this->record,
        ]));
    }

    private function hasInitialAttachment(): bool
    {
        return $this->record->attachments()
            ->where('stage', Attachment::STAGE_AWAL)
            ->exists();
    }

    private function lockInitialAttachments(): void
    {
        $this->record->attachments()
            ->where('stage', Attachment::STAGE_AWAL)
            ->update([
                'is_locked' => true,
            ]);
    }

    private function lockAllAttachments(): void
    {
        $this->record->attachments()
            ->update([
                'is_locked' => true,
            ]);
    }

    private function canProcessByStaff(): bool
    {
        return auth()->user()?->hasAnyRole([
            'admin',
            'super_admin',
            'staf',
            'tenaga_ahli',
        ]) ?? false;
    }

    private function canApprove(): bool
    {
        return auth()->user()?->hasAnyRole([
            'admin',
            'super_admin',
            'anggota_dewan',
        ]) ?? false;
    }

    private function isAnggotaDewan(): bool
    {
        return auth()->user()?->hasRole('anggota_dewan') ?? false;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Aspirasi berhasil diperbarui')
            ->body('Data aspirasi dan catatan tindak lanjut berhasil disimpan.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', [
            'record' => $this->record,
        ]);
    }
}