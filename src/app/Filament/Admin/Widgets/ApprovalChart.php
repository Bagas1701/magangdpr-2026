<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Aspirasi;
use Filament\Widgets\ChartWidget;

class ApprovalChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Approval Aspirasi';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $labels = [
            'Pending',
            'Revisi',
            'Disetujui',
            'Ditolak',
        ];

        $data = [
            Aspirasi::where('approval_status', 'pending')->count(),
            Aspirasi::where('approval_status', 'revision')->count(),
            Aspirasi::where('approval_status', 'approved')->count(),
            Aspirasi::where('approval_status', 'rejected')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => $data,
                    'backgroundColor' => [
                        '#94a3b8',
                        '#f59e0b',
                        '#22c55e',
                        '#ef4444',
                    ],

                    'borderColor' => [
                        '#64748b',
                        '#d97706',
                        '#16a34a',
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
        return 'doughnut';
    }
}