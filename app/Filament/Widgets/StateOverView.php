<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TransaksiResource;
use App\Models\Transaksi;
use App\Models\TransaksiWarga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StateOverView extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected static ?int $sort = 1;

    protected static bool $isLazy = true;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Permintaan', Transaksi::query()->where('status', 'Requested')->count())
                ->description('Jumlah Permintaan yang Harus Diperiksa')
                ->icon('heroicon-m-square-3-stack-3d')
                ->color('danger'),
            Stat::make('Total Pendapatan', TransaksiWarga::sum('price'))
                ->description('Peningkatan Pendapatan')
                ->icon('heroicon-m-circle-stack')
                ->color('success')
                ->chart([3,6,4,6,7,8,]),
            Stat::make('Total Berat', TransaksiWarga::sum('berat').' kg')
                ->description('Berat Sampah di Total')
                ->icon('heroicon-m-scale')
                ->color('warning')
                ->chart([3,10,4,6,3,8,]),

        ];
    }
}
