<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Pages;

use App\Filament\Admin\Resources\AspirasiResource;
use App\Models\Aspirasi;
use App\Models\AspirasiStatusHistory;
use App\Models\StatusAspirasi;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAspirasi extends CreateRecord
{
    protected static string $resource = AspirasiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        $data['status_id'] = StatusAspirasi::where('nama', Aspirasi::STATUS_MASUK)->value('id');

        $data['approval_status'] = 'pending';
        $data['approved_by'] = null;
        $data['approved_at'] = null;
        $data['approval_note'] = null;

        return $data;
    }

    protected function afterCreate(): void
    {
        $statusName = $this->record->status?->nama ?? Aspirasi::STATUS_MASUK;

        AspirasiStatusHistory::create([
            'aspirasi_id' => $this->record->id,
            'old_status' => '-',
            'new_status' => $statusName,
            'changed_by' => auth()->id(),
            'catatan' => 'Aspirasi dibuat dan masuk ke tahap awal.',
        ]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Aspirasi berhasil dibuat');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}