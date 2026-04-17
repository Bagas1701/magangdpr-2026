<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Pages;

use App\Filament\Admin\Resources\AspirasiResource;
use App\Models\AspirasiStatusHistory;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class EditAspirasi extends EditRecord
{
    protected static string $resource = AspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()?->isAdminLevel()),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $user = auth()->user();

        $oldStatus = $record->status;
        $newStatus = $data['status'] ?? $oldStatus;
        $statusChangeNote = trim($data['status_change_note'] ?? '');
        unset($data['status_change_note']);

        $allowedTransitionsByRole = [
            'super_admin' => [
                'Masuk' => ['Masuk', 'Verifikasi'],
                'Verifikasi' => ['Verifikasi', 'Tindak Lanjut'],
                'Tindak Lanjut' => ['Tindak Lanjut', 'Selesai'],
                'Selesai' => ['Selesai'],
            ],
            'admin' => [
                'Masuk' => ['Masuk', 'Verifikasi'],
                'Verifikasi' => ['Verifikasi', 'Tindak Lanjut'],
                'Tindak Lanjut' => ['Tindak Lanjut', 'Selesai'],
                'Selesai' => ['Selesai'],
            ],
            'anggota' => [
                'Masuk' => ['Masuk', 'Verifikasi'],
                'Verifikasi' => ['Verifikasi', 'Tindak Lanjut'],
                'Tindak Lanjut' => ['Tindak Lanjut', 'Selesai'],
                'Selesai' => ['Selesai'],
            ],
            'tenaga_ahli' => [
                'Masuk' => ['Masuk', 'Verifikasi'],
                'Verifikasi' => ['Verifikasi', 'Tindak Lanjut'],
                'Tindak Lanjut' => ['Tindak Lanjut'],
                'Selesai' => ['Selesai'],
            ],
            'staf' => [
                'Masuk' => ['Masuk', 'Verifikasi'],
                'Verifikasi' => ['Verifikasi', 'Tindak Lanjut'],
                'Tindak Lanjut' => ['Tindak Lanjut'],
                'Selesai' => ['Selesai'],
            ],
        ];

        $userRole = $user?->hasRole('super_admin')
            ? 'super_admin'
            : ($user?->hasRole('admin')
                ? 'admin'
                : ($user?->hasRole('anggota')
                    ? 'anggota'
                    : ($user?->hasRole('tenaga_ahli')
                        ? 'tenaga_ahli'
                        : ($user?->hasRole('staf') ? 'staf' : null))));

        if (! $userRole) {
            $data['status'] = $oldStatus;
            $newStatus = $oldStatus;
        } else {
            $allowedTransitions = $allowedTransitionsByRole[$userRole][$oldStatus] ?? [$oldStatus];

            if (! in_array($newStatus, $allowedTransitions, true)) {
                throw ValidationException::withMessages([
                    'status' => "Role {$userRole} tidak diizinkan mengubah status dari {$oldStatus} ke {$newStatus}.",
                ]);
            }
        }

        if ($oldStatus !== $newStatus && $statusChangeNote === '') {
            throw ValidationException::withMessages([
                'status_change_note' => 'Catatan perubahan status wajib diisi jika status diubah.',
            ]);
        }

        $oldApprovalStatus = $record->approval_status ?? 'pending';
        $newApprovalStatus = $data['approval_status'] ?? $oldApprovalStatus;
        $approvalNote = trim($data['approval_note'] ?? ($record->approval_note ?? ''));

        $canManageApproval = $user?->canApproveAspirasi();

        if (! $canManageApproval) {
            $data['approval_status'] = $oldApprovalStatus;
            $data['approval_note'] = $record->approval_note;
            $data['approved_by'] = $record->approved_by;
            $data['approved_at'] = $record->approved_at;
            $newApprovalStatus = $oldApprovalStatus;
        } else {
            if (
                $oldApprovalStatus !== $newApprovalStatus
                && in_array($newApprovalStatus, ['approved', 'rejected'], true)
                && $approvalNote === ''
            ) {
                throw ValidationException::withMessages([
                    'approval_note' => 'Catatan approval wajib diisi saat approve atau reject.',
                ]);
            }

            if (in_array($newApprovalStatus, ['approved', 'rejected'], true)) {
                $data['approved_by'] = $user?->id;
                $data['approved_at'] = now();
                $data['approval_note'] = $approvalNote;
            } elseif ($newApprovalStatus === 'pending') {
                $data['approved_by'] = null;
                $data['approved_at'] = null;
                $data['approval_note'] = $approvalNote !== '' ? $approvalNote : null;
            }
        }

        $record->update($data);

        if ($oldStatus !== $newStatus) {
            AspirasiStatusHistory::create([
                'aspirasi_id' => $record->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => $user?->id,
                'catatan' => $statusChangeNote,
            ]);
        }

        return $record;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Aspirasi berhasil diperbarui');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}