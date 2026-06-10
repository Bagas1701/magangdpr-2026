<?php

namespace App\Filament\Admin\Resources\StatusAspirasiResource\Pages;

use App\Filament\Admin\Resources\StatusAspirasiResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStatusAspirasi extends ViewRecord
{
    protected static string $resource = StatusAspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}