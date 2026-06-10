<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Aspirasi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikAspirasi extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $total = Aspirasi::count();

        $diproses = Aspirasi::whereHas(
            'status',
            fn ($query) => $query->whereIn('nama', [
                Aspirasi::STATUS_MASUK,
                Aspirasi::STATUS_VERIFIKASI,
                Aspirasi::STATUS_TINDAK_LANJUT,
            ])
        )->count();

        $menunggu = Aspirasi::whereHas(
            'status',
            fn ($query) => $query->where(
                'nama',
                Aspirasi::STATUS_MENUNGGU_PERSETUJUAN
            )
        )->count();

        $selesai = Aspirasi::whereHas(
            'status',
            fn ($query) => $query->where(
                'nama',
                Aspirasi::STATUS_SELESAI
            )
        )->count();

        $ditolak = Aspirasi::whereHas(
            'status',
            fn ($query) => $query->where(
                'nama',
                Aspirasi::STATUS_DITOLAK
            )
        )->count();

        return [

            Stat::make('Total Aspirasi', $total)
                ->description('Seluruh aspirasi yang tercatat')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->chart([7, 12, 10, 18, 15, 25, 30]),

            Stat::make('Dalam Proses', $diproses)
                ->description('Masuk, Verifikasi, Tindak Lanjut')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning')
                ->chart([30, 20, 25, 18, 15, 10, 8]),

            Stat::make('Menunggu Approval', $menunggu)
                ->description('Menunggu review anggota dewan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info')
                ->chart([3, 4, 5, 2, 6, 3, 1]),

            Stat::make('Selesai', $selesai)
                ->description('Aspirasi telah final')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([1, 3, 5, 8, 10, 12, 15]),

            Stat::make('Ditolak', $ditolak)
                ->description('Aspirasi ditolak')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->chart([1, 2, 1, 3, 2, 1, 1]),

        ];
    }
}