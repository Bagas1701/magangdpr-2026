<?php

namespace App\Filament\Admin\Resources\KategoriAspirasiResource\Pages;

use App\Filament\Admin\Resources\KategoriAspirasiResource;
use Filament\Resources\Pages\ViewRecord;

class ViewKategoriAspirasi extends ViewRecord
{
    protected static string $resource = KategoriAspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}