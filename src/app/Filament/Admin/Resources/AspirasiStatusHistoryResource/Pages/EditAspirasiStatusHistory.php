<?php

namespace App\Filament\Admin\Resources\AspirasiStatusHistoryResource\Pages;

use App\Filament\Admin\Resources\AspirasiStatusHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAspirasiStatusHistory extends EditRecord
{
    protected static string $resource = AspirasiStatusHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
