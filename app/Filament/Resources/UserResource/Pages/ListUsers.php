<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getCreateActions(): Action
    {
        return parent::getCreateActions()->label('Tambah Transaksi');
    }
}
