<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\TransaksiWarga;
use App\Filament\Resources\TransaksiResource;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StateOverView extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $pollingInterval = '15s';

    protected static ?int $sort = 1;

    // protected static bool $isLazy = false;
    protected function getStats(): array
    {
        // $totalPendapatan = new Transaksi();
        $totalPendapatan = TransaksiWarga::whereHas('transaksi', function($query) {
            $query->whereNotIn('status',['Ditolak', 'Requested']);
            })->sum('price');
        $totalBerat = TransaksiWarga::whereHas('transaksi', function($query) {
            $query->whereNotIn('status',['Ditolak', 'Requested']);
            })->sum('berat');
                return [
                    Stat::make('Total Permintaan', Transaksi::query()->where('status', 'Requested')->count())
                        ->description('Jumlah Permintaan yang Harus Diperiksa')
                        ->icon('heroicon-m-square-3-stack-3d')
                        ->color('danger'),
                    Stat::make('Total Pendapatan', 'Rp'.number_format($totalPendapatan, 0, ',', '.'))
                    // ->sum('price'); // Calculate total price for the month
                        ->description('Peningkatan Pendapatan')
                        ->icon('heroicon-m-circle-stack')
                        ->color('success')
                        ->chart([3,6,4,6,7,8,]),
                    Stat::make('Total Berat', number_format($totalBerat).' kg')
                        ->description('Berat Sampah di Total')
                        ->icon('heroicon-m-scale')
                        ->color('warning')
                        ->chart([3,10,4,6,3,8,]),

        ];
    }
}

