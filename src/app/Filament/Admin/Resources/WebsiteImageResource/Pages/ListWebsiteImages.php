<?php

namespace App\Filament\Admin\Resources\WebsiteImageResource\Pages;

use App\Filament\Admin\Resources\WebsiteImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteImages extends ListRecords
{
    protected static string $resource = WebsiteImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
