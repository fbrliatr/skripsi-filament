<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\TransaksiWarga;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\TransaksiResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class TotalTransaksi extends ChartWidget
{


    protected static ?string $heading = 'Grafik Jumlah Transaksi';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;

    // protected function getData(): array
    // {
    //     $data = $this->getTotalTransaksiPerBulan();
    //     return [
    //         'datasets' =>[
    //             [
    //                 'label'=> 'Jumlah Permintaan',
    //                 'data' => $data['totalTransaksiPerBulan'],
    //             ]
    //         ],
    //         'labels'=> $data['bulan'],
    //     ];
    // }

    protected function getType(): string
    {
        return 'line';
    }

    // private function getTotalTransaksiPerBulan(): array
    // {
    //     $now = Carbon::now();
    //     $totalTransaksiPerBulan = [];
    //     $months = [];

    //     for ($month = 1; $month <= 12; $month++) {
    //         $startOfMonth = Carbon::create($now->year, $month, 1)->startOfMonth();
    //         $endOfMonth = Carbon::create($now->year, $month, 1)->endOfMonth();

    //         $count = Transaksi::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
    //         $totalTransaksiPerBulan[] = $count;
    //         $months[] = $startOfMonth->format('M');
    //     }

    //     return [
    //         'totalTransaksiPerBulan' => $totalTransaksiPerBulan,
    //         'bulan' => $months,
    //     ];
    // }
    protected function getData(): array
    {
        $data = $this->getTotalPendapatanPerBulan();
        return [
            'datasets' =>[
                [
                    'label'=> 'Jumlah Transaksi',
                    'data' => $data['total'],
                ],
            ],
            'labels'=> $data['bulan'],
        ];
    }
    protected function getTotalPendapatanPerBulan(): array
    {
        $now = Carbon::now();
        $total = []; // Initialize total array
        $months = [];
        $user = Auth::user();

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::create($now->year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($now->year, $month, 1)->endOfMonth();

            // Get total transactions for the month

            if ($user->hasRole('Bank Pusat')) {
                $totalbulan = TransaksiWarga::whereHas('transaksi', function($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                    ->whereNotIn('status', ['Ditolak', 'Requested']);
                });
            }
            else {
                $totalbulan = TransaksiWarga::whereHas('transaksi', function($query) use ($startOfMonth, $endOfMonth, $user) {
                    $query
                        ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                        ->whereNotIn('status', ['Ditolak', 'Requested'])
                        ->where('bank_unit_name',$user->bank_unit);
                    });
            }
            $total[] = $totalbulan->count();
            $months[] = $startOfMonth->format('M');
        }

        return [
            'bulan' => $months,
            'total' => $total
        ];
    }
}
