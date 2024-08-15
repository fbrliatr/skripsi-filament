<?php

namespace App\Filament\Resources\WargaResource\Pages;

use Filament\Tables;
use App\Models\Warga;
use Filament\Infolists;
use Filament\Tables\Table;
use Filament\Pages\Actions;
use App\Models\TransaksiWarga;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\WargaResource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ViewWarga extends ViewRecord
{
    protected static string $resource = WargaResource::class;

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         \Filament\Infolists\Components\InfoList::make()
    //             ->schema([
    //                 Section::make('Data Diri')
    //                     ->schema([
    //                         TextEntry::make('name')
    //                             ->label('Nama')
    //                             ->getStateUsing(fn (Warga $record): string => $record->name),
    //                         TextEntry::make('no_hp')
    //                             ->label('Nomor HP')
    //                             ->getStateUsing(fn (Warga $record): string => $record->no_hp),
    //                         TextEntry::make('bank_unit')
    //                             ->label('Bank Unit')
    //                             ->getStateUsing(fn (Warga $record): string => $record->bank_unit),
    //                         TextEntry::make('email')
    //                             ->label('Email')
    //                             ->getStateUsing(fn (Warga $record): string => $record->email),
    //                     ])->columns(2),
    //             ]),
    //     ];
    // }

    // protected function getTableQuery(): Builder
    // {
    //     return TransaksiWarga::query()->where('warga_id', $this->record->id);
    // }

    // protected function Table(Table $table): table
    // {
    //     return $table
    //         ->query(TransaksiWarga::query()->where('warga_id', $this->record->id))
    //         ->defaultPaginationPageOption(5)
    //         ->columns([
    //             Tables\Columns\TextColumn::make('warga.name')
    //                 ->label('Nama Warga'),
    //             Tables\Columns\TextColumn::make('transaksi.code')
    //                 ->label('Kode Transaksi')
    //                 ->sortable(),
    //             Tables\Columns\TextColumn::make('transaksi.tanggal')
    //                 ->label('Tanggal Transaksi')
    //                 ->date()
    //                 ->sortable(),
    //             Tables\Columns\TextColumn::make('berat')
    //                 ->label('Berat')
    //                 ->sortable(),
    //             Tables\Columns\TextColumn::make('price')
    //                 ->label('Harga')
    //                 ->sortable(),
    //         ]);

    // }


    // protected function getTable(): Tables\Table
    // {
    //     return Tables\Table::make()
    //         ->columns($this->getTableColumns())
    //         ->query($this->getTableQuery())
    //         ->defaultPaginationPageOption(5);
    // }

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
