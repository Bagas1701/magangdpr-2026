<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Aspirasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikAspirasi extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Aspirasi', Aspirasi::count())
            ->color('primary'),
            Stat::make('Aspirasi Masuk', Aspirasi::where('status', 'Masuk')->count())
            ->color('danger'),
            Stat::make('Aspirasi Verifikasi', Aspirasi::where('status', 'Verifikasi')->count())
            ->color('warning'),
            Stat::make('Aspirasi Tindak Lanjut', Aspirasi::where('status', 'Tindak Lanjut')->count())
            ->color('info'),
            Stat::make('Aspirasi Selesai', Aspirasi::where('status', 'Selesai')->count())
            ->color('success'),

        ];
    }
}
