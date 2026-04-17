<?php

namespace App\Filament\Admin\Resources\KategoriAspirasiResource\Pages;

use App\Filament\Admin\Resources\KategoriAspirasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriAspirasi extends EditRecord
{
    protected static string $resource = KategoriAspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
