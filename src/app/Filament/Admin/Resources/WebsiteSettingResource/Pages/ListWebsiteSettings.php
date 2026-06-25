<?php

namespace App\Filament\Admin\Resources\WebsiteSettingResource\Pages;

use App\Filament\Admin\Resources\WebsiteSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteSettings extends ListRecords
{
    protected static string $resource = WebsiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
