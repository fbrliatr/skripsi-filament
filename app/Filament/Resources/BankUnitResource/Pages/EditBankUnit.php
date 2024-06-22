<?php

namespace App\Filament\Resources\BankUnitResource\Pages;

use App\Filament\Resources\BankUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankUnit extends EditRecord
{
    protected static string $resource = BankUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
