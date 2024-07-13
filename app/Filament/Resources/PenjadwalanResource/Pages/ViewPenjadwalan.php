<?php

namespace App\Filament\Resources\PenjadwalanResource\Pages;

use App\Filament\Resources\PenjadwalanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPenjadwalan extends ViewRecord
{
    protected static string $resource = PenjadwalanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
