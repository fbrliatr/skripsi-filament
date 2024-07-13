<?php

namespace App\Filament\Resources\PenjadwalanResource\Pages;

use Filament\Actions;
use App\Models\BankUnit;
use App\Models\DaftarRute;
use App\Models\Penjadwalan;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PenjadwalanResource;

class ListPenjadwalans extends ListRecords
{
    protected static string $resource = PenjadwalanResource::class;
    protected function getHeaderWidgets(): array
    {
        return [
            PenjadwalanResource\Widgets\Rute::class,
        ];
    }
    public function getActions(): array
    {
        return [
            Action::make('buat_jadwal')
            ->label('Buat Jadwal')
            ->modalHeading('Buat Jadwal Baru')
            ->modalButton('Buat')
            ->action(function (array $data) {
                $bankUnitId = $data['bank_unit'];
                $tanggal = $data['tgl_angkut'];

                // Ambil jarak dan jam_angkut dari penjadwalan berdasarkan id_penjadwalan
                $penjadwalan = Penjadwalan::where('tgl_angkut', $tanggal)
                    ->where('id_bank_unit', $bankUnitId)
                    ->first();

                if ($penjadwalan) {
                    $jarak = $penjadwalan->jarak;
                    $jamAngkut = $penjadwalan->jam_angkut;
                    $routeResult = $this->calculateShortestRoute($bankUnitId, $tanggal);

                    // Simpan ke dalam daftar_rute jika ada hasil
                        if ($routeResult['total_jarak'] > 0) {
                            DaftarRute::create([
                                'route' => $routeResult['route'],
                                'tgl_angkut' => $tanggal,
                                'jam_angkut' => $jamAngkut, // Pastikan jamAngkut ditentukan sebelumnya
                                'jarak' => $routeResult['total_jarak'],
                                'urutan' => 1, // Atur urutan sesuai kebutuhan
                            ]);
                        } else {
                            // Handle kasus jika tidak ada rute yang ditemukan
                            return 'Tidak ada rute yang tersedia untuk tanggal tersebut.';
                        }

                    return [
                        'notify' => ['success', 'Rute berhasil dibuat: ' . $routeResult['route']],
                        'closeModal' => true,
                    ];
                } else {
                    return [
                        'notify' => ['error', 'Penjadwalan tidak ditemukan untuk bank unit dan tanggal yang dipilih.'],
                    ];
                }
            })
            ->form([
                Select::make('bank_unit')
                    ->label('Pilih Bank Unit')
                    ->options(BankUnit::pluck('name', 'id')) // Ambil semua bank unit
                    ->required()
                    ->multiple(),
                Select::make('tgl_angkut')
                    ->label('Tanggal')
                    ->options(Penjadwalan::select('tanggal')->distinct()->pluck('tanggal', 'tanggal')) // Mengambil tanggal unik
                    ->required(),
            ]),
        ];
    }

    private function calculateShortestRoute(array $bankUnits, $tanggal)
    {
        // Ambil data penjadwalan untuk bank unit yang dipilih pada tanggal yang diberikan
        $penjadwalans = Penjadwalan::where('tanggal', $tanggal)
            ->whereIn('id_bank_unit', $bankUnits)
            ->get();

        if ($penjadwalans->isEmpty()) {
            return [
                'route' => 'Tidak ada rute yang tersedia.',
                'total_jarak' => 0,
            ];
        }

        // Urutkan berdasarkan jam angkut (lebih pagi ke lebih sore) dan jarak dari terjauh ke terdekat
        $penjadwalans = $penjadwalans->sortBy('jam_angkut')->sortByDesc('jarak');

        // Total jarak
        $totalJarak = 0;
        $routes = [];
        $urutan = 1; // Mulai nomor urutan dari 1

        foreach ($penjadwalans as $penjadwalan) {
            $totalJarak += $penjadwalan->jarak; // Tambahkan jarak
            $routes[] = $penjadwalan->bankUnit->name; // Menyimpan nama bank unit
        }

        $routes = array_unique($routes);

        // Kembalikan hasil
        return [
            'route' => implode(' -> ', $routes),
            'total_jarak' => $totalJarak,
            $urutan ++
        ];
    }

}
