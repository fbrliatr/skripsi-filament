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
        return DB::transaction(function () use ($data) {
            $transaksi = Transaksi::create([
                'code' => $data['code'],
                'bank_unit_id' => $data['bank_unit_id'],
                'kategori' => $data['kategori'],
                'status' => $data['status'],
                'tanggal' => $data['tanggal'],
                'price' => $data['price'],
                // 'warga_id' => $data['warga_id'],
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
