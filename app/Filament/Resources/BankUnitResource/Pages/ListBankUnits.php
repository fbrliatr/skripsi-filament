<?php

namespace App\Filament\Resources\BankUnitResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BankUnitResource;

class ListBankUnits extends ListRecords
{
    protected static string $resource = BankUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

}
