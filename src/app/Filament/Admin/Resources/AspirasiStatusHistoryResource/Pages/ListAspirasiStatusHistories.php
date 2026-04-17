<?php

namespace App\Filament\Admin\Resources\AspirasiStatusHistoryResource\Pages;

use App\Filament\Admin\Resources\AspirasiStatusHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAspirasiStatusHistories extends ListRecords
{
    protected static string $resource = AspirasiStatusHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
