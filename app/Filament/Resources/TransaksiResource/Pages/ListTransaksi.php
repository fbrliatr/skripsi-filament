<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksi extends ListRecords
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            // ->label(fn (): string => 'label '.$this->activeTab)
            // ->url(fn (): string => TransaksiResource::getUrl('create', ['activeTab' => $this->activeTab])),
        ];
    }


public function getTabs(): array
{
    return [
        'all' => Tab::make('Semua Transaksi'),
        'requested' => Tab::make('Requested')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Requested')),
        'diterima' => Tab::make('Diterima')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Diterima')),
        'dalamPerjalanan' => Tab::make('Dalam Perjalanan')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Dalam Perjalanan')),
        'menunggu' => Tab::make('Menunggu')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Menunggu')),
        'selesai' => Tab::make('Selesai')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Selesai')),
        'ditolak' => Tab::make('Ditolak')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Ditolak')),

    ];
}
}
