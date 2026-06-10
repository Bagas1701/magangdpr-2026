<?php

namespace App\Filament\Admin\Resources\KonstituenResource\Pages;

use App\Filament\Admin\Resources\KonstituenResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKonstituen extends ViewRecord
{
    protected static string $resource = KonstituenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Konstituen'),

            Actions\DeleteAction::make()
                ->label('Hapus')
                ->visible(fn (): bool => auth()->user()?->isAdminLevel() ?? false),
        ];
    }
}