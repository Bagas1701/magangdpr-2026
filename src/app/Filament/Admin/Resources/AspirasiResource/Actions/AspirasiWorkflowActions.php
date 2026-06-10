<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Actions;

use App\Models\Aspirasi;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables;

class AspirasiWorkflowActions
{
    public static function tableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->label('Lihat')
                ->icon('heroicon-o-eye'),

            Tables\Actions\EditAction::make()
                ->label('Edit')
                ->visible(fn (Aspirasi $record): bool =>
                    ! $record->isFinalState()
                    && auth()->user()?->hasAnyRole([
                        'admin',
                        'super_admin',
                        'staf',
                        'tenaga_ahli',
                    ])
                ),

            Tables\Actions\Action::make('mulai_verifikasi')
                ->label('Mulai Verifikasi')
                ->icon('heroicon-o-play')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn (Aspirasi $record): bool =>
                    $record->isMasuk()
                    && auth()->user()?->hasAnyRole([
                        'admin',
                        'super_admin',
                        'staf',
                    ])
                )
                ->action(function (Aspirasi $record): void {
                    $record->changeStatus(
                        Aspirasi::STATUS_VERIFIKASI,
                        'Aspirasi mulai diverifikasi oleh staf.'
                    );

                    Notification::make()
                        ->title('Status berhasil diubah ke Verifikasi')
                        ->success()
                        ->send();
                }),

            Tables\Actions\Action::make('verifikasi_lanjutkan')
                ->label('Verifikasi & Lanjutkan')
                ->icon('heroicon-o-arrow-right-circle')
                ->color('info')
                ->requiresConfirmation()
                ->visible(fn (Aspirasi $record): bool =>
                    $record->isVerifikasi()
                    && auth()->user()?->hasAnyRole([
                        'admin',
                        'super_admin',
                        'staf',
                    ])
                )
                ->action(function (Aspirasi $record): void {
                    $checklist = $record->verification_checklist ?? [];

                    $requiredChecklist = [
                        'surat_pengaduan',
                        'data_konstituen_sesuai',
                    ];

                    $missingChecklist = array_diff($requiredChecklist, $checklist);

                    if (! empty($missingChecklist)) {
                        Notification::make()
                            ->title('Verifikasi belum lengkap')
                            ->body('Checklist Surat Pengaduan dan Data Konstituen Sesuai wajib dicentang sebelum lanjut ke Tindak Lanjut.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $record->changeStatus(
                        Aspirasi::STATUS_TINDAK_LANJUT,
                        'Verifikasi selesai. Aspirasi masuk ke tahap tindak lanjut.'
                    );

                    Notification::make()
                        ->title('Aspirasi masuk ke tahap Tindak Lanjut')
                        ->success()
                        ->send();
                }),

            Tables\Actions\Action::make('selesaikan_tindak_lanjut')
                ->label('Selesaikan Proses')
                ->icon('heroicon-o-check-circle')
                ->color('primary')
                ->requiresConfirmation()
                ->visible(fn (Aspirasi $record): bool =>
                    $record->isTindakLanjut()
                    && auth()->user()?->hasAnyRole([
                        'admin',
                        'super_admin',
                        'staf',
                        'tenaga_ahli',
                    ])
                )
                ->action(function (Aspirasi $record): void {
                    $record->changeStatus(
                        Aspirasi::STATUS_MENUNGGU_PERSETUJUAN,
                        'Proses tindak lanjut selesai. Menunggu persetujuan anggota dewan.'
                    );

                    Notification::make()
                        ->title('Aspirasi dikirim ke Anggota Dewan')
                        ->success()
                        ->send();
                }),

            Tables\Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Aspirasi $record): bool =>
                    $record->isMenungguPersetujuan()
                    && auth()->user()?->hasAnyRole([
                        'admin',
                        'super_admin',
                        'anggota_dewan',
                    ])
                )
                ->action(function (Aspirasi $record): void {
                    $record->update([
                        'approval_status' => 'approved',
                        'approval_note' => null,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    $record->changeStatus(
                        Aspirasi::STATUS_SELESAI,
                        'Aspirasi disetujui oleh anggota dewan.'
                    );

                    Notification::make()
                        ->title('Aspirasi berhasil disetujui')
                        ->success()
                        ->send();
                }),

            Tables\Actions\Action::make('revisi')
                ->label('Revisi')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->form([
                    Textarea::make('approval_note')
                        ->label('Catatan Revisi')
                        ->required()
                        ->rows(3),
                ])
                ->visible(fn (Aspirasi $record): bool =>
                    $record->isMenungguPersetujuan()
                    && auth()->user()?->hasAnyRole([
                        'admin',
                        'super_admin',
                        'anggota_dewan',
                    ])
                )
                ->action(function (Aspirasi $record, array $data): void {
                    $record->update([
                        'approval_status' => 'revisi',
                        'approval_note' => $data['approval_note'],
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    $record->changeStatus(
                        Aspirasi::STATUS_TINDAK_LANJUT,
                        'Aspirasi dikembalikan untuk revisi: ' . $data['approval_note']
                    );

                    Notification::make()
                        ->title('Aspirasi dikembalikan ke Tindak Lanjut')
                        ->success()
                        ->send();
                }),

            Tables\Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Textarea::make('approval_note')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->rows(3),
                ])
                ->visible(fn (Aspirasi $record): bool =>
                    $record->isMenungguPersetujuan()
                    && auth()->user()?->hasAnyRole([
                        'admin',
                        'super_admin',
                        'anggota_dewan',
                    ])
                )
                ->action(function (Aspirasi $record, array $data): void {
                    $record->update([
                        'approval_status' => 'rejected',
                        'approval_note' => $data['approval_note'],
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    $record->changeStatus(
                        Aspirasi::STATUS_DITOLAK,
                        'Aspirasi ditolak oleh anggota dewan: ' . $data['approval_note']
                    );

                    Notification::make()
                        ->title('Aspirasi berhasil ditolak')
                        ->success()
                        ->send();
                }),

            Tables\Actions\DeleteAction::make()
                ->visible(fn (): bool => auth()->user()?->hasAnyRole([
                    'admin',
                    'super_admin',
                ]) ?? false),
        ];
    }
}