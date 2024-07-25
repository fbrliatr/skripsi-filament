<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use App\Filament\Resources\TransaksiResource;
use Filament\Widgets\TableWidget as BaseWidget;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class Permintaan extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $pollingInterval = '15s';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan= 'full';

    protected static bool $isLazy = true;

    public function table(Table $table): Table
    {

        return $table
            ->query(TransaksiResource::getEloquentQuery()->where('status','requested'))
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_unit_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_berat')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->totalBerat() . ' kg'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn($record) => 'Rp' .number_format($record->totalHarga())),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
