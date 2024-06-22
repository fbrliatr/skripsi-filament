<?php

namespace App\Filament\Resources\BankUnitResource\Pages;

use App\Filament\Resources\BankUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBankUnit extends ViewRecord
{
    protected static string $resource = BankUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
