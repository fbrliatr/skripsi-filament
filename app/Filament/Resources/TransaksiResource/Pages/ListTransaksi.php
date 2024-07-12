<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Models\Transaksi;
use Filament\Tabs;
use Filament\Tables;
use Filament\Actions;
use App\Models\Transaction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TransaksiResource;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\Pages\ListRecords\TabCollection;

class ListTransaksi extends ListRecords
{
    use ExposesTableToWidgets;
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
        'all' => Tab::make('Semua Transaksi')
            ->badge(Transaksi::all()->count())
            ->badgeColor('black'),
        'requested' => Tab::make('Requested')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Requested'))
            ->badge(Transaksi::query()->where('status', 'Requested')->count()),
        'diterima' => Tab::make('Diterima')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Diterima'))
            ->badge(Transaksi::query()->where('status', 'Diterima')->count())
            ->badgeColor('success'),
        'dalamPerjalanan' => Tab::make('Dalam Perjalanan')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Dalam Perjalanan'))
            ->badge(Transaksi::query()->where('status', 'Dalam Perjalanan')->count()),
        'menunggu' => Tab::make('Menunggu')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Menunggu'))
            ->badge(Transaksi::query()->where('status', 'Menunggu')->count()),
        'selesai' => Tab::make('Selesai')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Selesai'))
            ->badge(Transaksi::query()->where('status', 'Selesai')->count()),
        'ditolak' => Tab::make('Ditolak')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Ditolak'))
            ->badge(Transaksi::query()->where('status', 'Ditolak')->count())
            ->badgeColor('danger'),

    ];
}
}
