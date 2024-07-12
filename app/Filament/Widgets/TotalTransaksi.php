<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TransaksiResource;
use App\Models\Transaksi;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class TotalTransaksi extends ChartWidget
{
    protected static ?string $heading = 'Chart Permintaan';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $data = $this->getTotalTransaksiPerBulan();
        return [
            'datasets' =>[
                [
                    'label'=> 'Jumlah Permintaan',
                    'data' => $data['totalTransaksiPerBulan'],
                ]
            ],
            'labels'=> $data['bulan'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getTotalTransaksiPerBulan(): array
    {
        $now = Carbon::now();
        $totalTransaksiPerBulan = [];
        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::create($now->year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($now->year, $month, 1)->endOfMonth();

            $count = Transaksi::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $totalTransaksiPerBulan[] = $count;
            $months[] = $startOfMonth->format('M');
        }

        return [
            'totalTransaksiPerBulan' => $totalTransaksiPerBulan,
            'bulan' => $months,
        ];
    }
}
