<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Aspirasi;
use Filament\Widgets\ChartWidget;

class ChartAspirasi extends ChartWidget
{
    protected static ?string $heading = 'Monitoring Status Aspirasi';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [
            Aspirasi::STATUS_MASUK,
            Aspirasi::STATUS_VERIFIKASI,
            Aspirasi::STATUS_TINDAK_LANJUT,
            Aspirasi::STATUS_MENUNGGU_PERSETUJUAN,
            Aspirasi::STATUS_SELESAI,
            Aspirasi::STATUS_DITOLAK,
        ];

        $data = collect($labels)
            ->map(fn (string $status): int => Aspirasi::whereHas(
                'status',
                fn ($query) => $query->where('nama', $status)
            )->count())
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Aspirasi',
                    'data' => $data,
                    'backgroundColor' => [
                        '#94a3b8',
                        '#38bdf8',
                        '#f59e0b',
                        '#6366f1',
                        '#22c55e',
                        '#ef4444',
                    ],
                    'borderColor' => [
                        '#64748b',
                        '#0284c7',
                        '#d97706',
                        '#4f46e5',
                        '#16a34a',
                        '#dc2626',
                    ],
                    'borderWidth' => 1,
                    'borderRadius' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}