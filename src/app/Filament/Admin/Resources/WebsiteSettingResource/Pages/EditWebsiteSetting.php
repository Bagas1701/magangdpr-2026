<?php

namespace App\Filament\Admin\Resources\WebsiteSettingResource\Pages;

use App\Filament\Admin\Resources\WebsiteSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteSetting extends EditRecord
{
    protected static string $resource = WebsiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
