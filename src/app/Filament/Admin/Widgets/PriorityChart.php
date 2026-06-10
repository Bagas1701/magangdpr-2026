<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Aspirasi;
use Filament\Widgets\ChartWidget;

class PriorityChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Prioritas Aspirasi';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $labels = ['Rendah', 'Sedang', 'Tinggi', 'Mendesak'];

        $data = [
            Aspirasi::where('prioritas', 'rendah')->count(),
            Aspirasi::where('prioritas', 'sedang')->count(),
            Aspirasi::where('prioritas', 'tinggi')->count(),
            Aspirasi::where('prioritas', 'mendesak')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => $data,
                    'backgroundColor' => [
                        '#22c55e',
                        '#38bdf8',
                        '#f59e0b',
                        '#ef4444',
                    ],

                    'borderColor' => [
                        '#16a34a',
                        '#0284c7',
                        '#d97706',
                        '#dc2626',
                    ],

                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}