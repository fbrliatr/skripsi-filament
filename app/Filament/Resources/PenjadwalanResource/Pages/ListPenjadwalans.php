<?php
namespace App\Filament\Resources\PenjadwalanResource\Pages;

use Filament\Actions;
use App\Models\BankUnit;
use App\Models\DaftarRute;
use App\Models\Penjadwalan;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
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
                ->visible(function () {
                    return Auth::user()->hasRole('Bank Pusat');
                })
                ->modalHeading('Buat Jadwal Baru')
                ->modalButton('Buat')
                ->action(function (array $data) {
                    Log::info('Data yang diterima:', $data);
                    // Ubah data menjadi array jika belum
                    $bankUnitName = $data['bank_unit'];
                    $tanggal = $data['tgl_angkut'];

                    // Ambil jarak dan jam_angkut dari penjadwalan berdasarkan id_penjadwalan

                    $penjadwalan = Penjadwalan::where('tanggal', $tanggal)
                        ->whereIn('name_bank_unit', $bankUnitName)
                        ->first();
                        Log::info('penjadwalan yang diterima:', ['penjadwalans' => $penjadwalan->toArray()]);

                    if ($penjadwalan) {
                        $jarak = $penjadwalan->jarak;
                        $jamAngkut = $penjadwalan->jam_angkut;
                        $routeResult = $this->calculateShortestRoute($bankUnitName, $tanggal);
                        Log::info('Hasil Rute adalah', $routeResult);
                    //     if ($penjadwalan->isNotEmpty()) {
                    //         Log::info('Hasil penjadwalan:');
                    //     } else {
                    //         Log::info('Tidak ada penjadwalan yang ditemukan untuk tanggal dan bank unit yang dipilih.');
                    //     }
                    // }
                        // Simpan ke dalam daftar_rute jika ada hasil
                        if ($routeResult['total_jarak'] > 0) {
                            Log::info('Data diterima:', $routeResult);
                            DaftarRute::create([
                                'route' => $routeResult['routes'],
                                'tgl_angkut' => $tanggal,
                                'jam_angkut' => $jamAngkut,
                                'jarak' => $routeResult['total_jarak'],
                                'urutan' => 1,
                            ]);
                        } else {
                            // Handle kasus jika tidak ada rute yang ditemukan
                            return Log::info('Data tidak diterima');

                        }
                            return [
                                'notify' => ['success', 'Rute berhasil dibuat: ' . $routeResult['routes']],
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
                        ->options(BankUnit::pluck('name', 'name')) // Ambil semua bank unit
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
        $penjadwalan = Penjadwalan::where('tanggal', $tanggal)
            ->whereIn('name_bank_unit', $bankUnits)
            ->get();
            Log::info('Query penjadwalan:', ['penjadwalans' => $penjadwalan->toArray()]);

            // if ($penjadwalan->isNotEmpty()) {
            //     Log::info('Hasil penjadwalan:', ['penjadwalans' => $penjadwalan->toArray()]);
            // } else {
            //     Log::info('Tidak ada penjadwalan yang ditemukan untuk tanggal dan bank unit yang dipilih.');
            // }
        if ($penjadwalan->isEmpty()) {
            return [
                'routes' => 'Tidak ada rute yang tersedia.',
                'total_jarak' => 0,
            ];
        }

        // Urutkan berdasarkan jam angkut (lebih pagi ke lebih sore) dan jarak dari terjauh ke terdekat
        $penjadwalans = $penjadwalan->sortBy('jam_angkut')->sortByDesc('jarak');
        Log::info('Hasil penjadwalan:', ['penjadwalans' => $penjadwalans->toArray()]);
        // Total jarak
        $totalJarak = 0;
        $routes = [];
        $urutan = 1;
        Log::info('Hasil Rute:', $routes);
        foreach ($penjadwalans as $jadwal) {
            $totalJarak += $jadwal->jarak;
            $routes[] = $jadwal->name_bank_unit;
        }

        $routes = array_unique($routes);

        // Kembalikan hasil
        return [
            'routes' => implode(' -> ', $routes),
            'total_jarak' => $totalJarak,
        ];
    }
}
