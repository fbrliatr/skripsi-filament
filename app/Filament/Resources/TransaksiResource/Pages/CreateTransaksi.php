<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;


class CreateTransaksi extends CreateRecord
{
    protected static string $resource = TransaksiResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        \Log::info('Data diterima untuk pembuatan transaksi:', $data);

        if (!isset($data['transaksiWargas']) || !is_array($data['transaksiWargas'])) {
            $data['transaksiWargas'] = [];
        }
        return DB::transaction(function () use ($data) {
            $transaksi = Transaksi::create([
                'code' => $data['code'],
                'bank_unit_id' => $data['bank_unit_id'],
                'kategori' => $data['kategori'],
                'status' => $data['status'],
                'tanggal' => $data['tanggal'],
                'jam_angkut' => $data['jam_angkut'],
            ]);
            foreach ($data['transaksiWargas'] as $wargaData) {
                $transaksi->transaksiWargas()->create([
                'warga_id' => $wargaData['warga_id'],
                'berat' => $wargaData['berat'],
                'price' => $wargaData['price'],
                ]);
            }

            return $transaksi;
        });
    }
}
