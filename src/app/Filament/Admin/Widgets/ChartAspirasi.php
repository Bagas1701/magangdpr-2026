<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Aspirasi;
use Filament\Widgets\ChartWidget;

class ChartAspirasi extends ChartWidget
{
    protected static ?string $heading = 'Statistik Aspirasi per Kategori';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Aspirasi',
                    'data' => [
                        Aspirasi::whereHas('kategoriAspirasi', fn ($query) => $query->where('nama', 'Pidana'))->count(),
                        Aspirasi::whereHas('kategoriAspirasi', fn ($query) => $query->where('nama', 'Perdata'))->count(),
                        Aspirasi::whereHas('kategoriAspirasi', fn ($query) => $query->where('nama', 'HAM'))->count(),
                        Aspirasi::whereHas('kategoriAspirasi', fn ($query) => $query->where('nama', 'Agraria'))->count(),
                        Aspirasi::whereHas('kategoriAspirasi', fn ($query) => $query->where('nama', 'Lainnya'))->count(),
                    ],
                ],
            ],
            'labels' => ['Pidana', 'Perdata', 'HAM', 'Agraria', 'Lainnya'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}