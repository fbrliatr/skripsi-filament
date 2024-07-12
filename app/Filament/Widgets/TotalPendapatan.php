<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TransaksiResource;
use App\Models\Transaksi;
use App\Models\TransaksiWarga;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class TotalPendapatan extends ChartWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static ?string $heading = 'Chart Pendapatan';
    protected static ?int $sort = 2;
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $data = $this->getTotalPendapatanPerBulan();
        return [
            'datasets' =>[
                [
                    'label'=> 'Jumlah Transaksi',
                    'data' => $data['totalPendapatanPerBulan'],
                ],
                [
                    'label'=> 'Total Transaksi per Bulan',
                    'data'=> $data['total'],
                ],
            ],
            'labels'=> $data['bulan'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getTotalPendapatanPerBulan(): array
    {
        $now = Carbon::now();
        $totalPendapatanPerBulan = [];
        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::create($now->year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($now->year, $month, 1)->endOfMonth();

            $PendapatanPerBulan = Transaksi::whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            $PendapatanPerBulan2 = TransaksiWarga::whereBetween('created_at', [$startOfMonth, $endOfMonth]);

            $count = $PendapatanPerBulan->count();
            $totalPendapatan = $PendapatanPerBulan2->sum('price');

            $totalPendapatanPerBulan[] = $count;
            $total[] = $totalPendapatan;
            $months[] = $startOfMonth->format('M');
        }

        return [
            'totalPendapatanPerBulan' => $totalPendapatanPerBulan,
            'total' => $total,
            'bulan' => $months,
        ];
    }
}
