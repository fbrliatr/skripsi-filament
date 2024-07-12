<?php

namespace App\Filament\Resources\TransaksiWargaResource\Pages;

use App\Filament\Resources\TransaksiWargaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaksiWarga extends ViewRecord
{
    protected static string $resource = TransaksiWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
