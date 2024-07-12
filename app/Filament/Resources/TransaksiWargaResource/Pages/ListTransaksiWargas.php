<?php

namespace App\Filament\Resources\TransaksiWargaResource\Pages;

use App\Filament\Resources\TransaksiWargaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiWargas extends ListRecords
{
    protected static string $resource = TransaksiWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
