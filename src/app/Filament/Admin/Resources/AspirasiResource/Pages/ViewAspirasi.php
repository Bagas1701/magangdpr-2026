<?php

namespace App\Filament\Admin\Resources\AspirasiResource\Pages;

use App\Filament\Admin\Resources\AspirasiResource;
use App\Filament\Admin\Resources\AspirasiResource\Widgets\ActivityTimelineWidget;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAspirasi extends ViewRecord
{
    protected static string $resource = AspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetakPdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn () => route('aspirasi.pdf', ['aspirasi' => $this->record]))
                ->openUrlInNewTab(),
                
            Actions\EditAction::make()
                ->label(fn (): string =>
                    auth()->user()?->hasRole('anggota_dewan')
                        ? 'Review Aspirasi'
                        : 'Edit Aspirasi'
                )
                ->visible(fn (): bool => ! $this->record->isFinalState()),

            Actions\DeleteAction::make()
                ->visible(fn (): bool => auth()->user()?->isAdminLevel() ?? false),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            ActivityTimelineWidget::class,
        ];
    }

    protected function getFooterWidgetsData(): array
    {
        return [
            'record' => $this->record,
        ];
    }
}