<?php

namespace App\Filament\Resources\TransaksiWargaResource\Pages;

use App\Filament\Resources\TransaksiWargaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiWarga extends EditRecord
{
    protected static string $resource = TransaksiWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
