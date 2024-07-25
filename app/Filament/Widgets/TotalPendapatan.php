<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Warga;
use App\Models\Transaksi;
use App\Models\TransaksiWarga;
use Filament\Widgets\ChartWidget;
use App\Filament\Resources\TransaksiResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class TotalPendapatan extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $pollingInterval = '15s';
    protected static ?string $heading = 'Chart Pendapatan';
    protected static ?int $sort = 2;
    // protected static bool $isLazy = true;

    protected function getData(): array
    {
        $data = $this->getTotalPendapatanPerBulan();
        return [
            'datasets' =>[
                [
                    'label'=> 'Jumlah Transaksi',
                    'data' => $data['total'],
                ],
                [
                    'label'=> 'Total Transaksi per Bulan',
                    'data'=> $data['totalPendapatanPerBulan'],
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
    $total = []; // Initialize total array
    $months = [];

    for ($month = 1; $month <= 12; $month++) {
        $startOfMonth = Carbon::create($now->year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($now->year, $month, 1)->endOfMonth();

        // Get total transactions for the month
        $totalbulan = TransaksiWarga::whereHas('transaksi', function($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                  ->whereNotIn('status', ['Ditolak', 'Requested']);
        });
        $totalPendapatan = TransaksiWarga::whereHas('transaksi', function($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                  ->whereNotIn('status', ['Ditolak', 'Requested']);
        }); // Ensure the status conditions

        // Append the total pendapatan for the month
        $totalPendapatanPerBulan[] = $totalPendapatan->sum('price');
        $total[] = $totalbulan->count();
        $months[] = $startOfMonth->format('M');
    }

    return [
        'totalPendapatanPerBulan' => $totalPendapatanPerBulan,
        'bulan' => $months,
        'total' => $total
    ];
}

}
