<?php

namespace App\Filament\Resources\PenjadwalanResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\DaftarRute; // Pastikan untuk mengimpor model yang sesuai

class Rute extends BaseWidget
{
    public function table(Table $table): Table
    {
        \Log::info('Table method called in Rute widget');
        return $table
            ->query(DaftarRute::query())
            ->defaultPaginationPageOption(2)
            ->columns([
                Tables\Columns\TextColumn::make('route')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_angkut')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_angkut')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('H:i') : 'N/A'), // Format jam dan menit

            ]);
    }
}
