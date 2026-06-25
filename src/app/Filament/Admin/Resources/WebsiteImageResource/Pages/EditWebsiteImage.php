<?php

namespace App\Filament\Admin\Resources\WebsiteImageResource\Pages;

use App\Filament\Admin\Resources\WebsiteImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteImage extends EditRecord
{
    protected static string $resource = WebsiteImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
