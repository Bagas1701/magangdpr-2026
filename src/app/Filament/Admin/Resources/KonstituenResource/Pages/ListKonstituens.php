<?php

namespace App\Filament\Admin\Resources\KonstituenResource\Pages;

use App\Filament\Admin\Resources\KonstituenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKonstituens extends ListRecords
{
    protected static string $resource = KonstituenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
