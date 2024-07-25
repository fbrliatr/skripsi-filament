<?php

namespace App\Filament\Resources\WargaResource\Pages;

use App\Filament\Resources\WargaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWargas extends ListRecords
{
    protected static string $resource = WargaResource::class;
    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('view');
    // }
    protected function getHeaderActions(): array
    {
        return [
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
