<?php

namespace App\Filament\Resources\TransaksiWargaResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TransaksiWargaResource;

class ListTransaksiWargas extends ListRecords
{
    protected static string $resource = TransaksiWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->visible(fn () => Auth::user()->hasRole(['Bank Unit', 'Bank Pusat'])),

        ];
    }
}
