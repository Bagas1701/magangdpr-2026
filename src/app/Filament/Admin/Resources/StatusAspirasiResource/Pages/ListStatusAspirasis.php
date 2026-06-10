<?php

namespace App\Filament\Admin\Resources\StatusAspirasiResource\Pages;

use App\Filament\Admin\Resources\StatusAspirasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatusAspirasis extends ListRecords
{
    protected static string $resource = StatusAspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
