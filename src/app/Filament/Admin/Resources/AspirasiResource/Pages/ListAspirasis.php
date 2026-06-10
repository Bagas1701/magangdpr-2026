<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Pages;

use App\Filament\Admin\Resources\AspirasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAspirasis extends ListRecords
{
    protected static string $resource = AspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(route('admin.aspirasi.export.excel'))
                ->openUrlInNewTab(),
        ];
    }
}
