<?php

namespace App\Filament\Resources\WargaResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\TransaksiWarga;

class DaftarTransaksi extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(function (Builder $query) {
            $user = Auth::user();
            if (!$user) {
                // Jika tidak ada pengguna yang login, tidak mengembalikan apapun
                return $query->whereRaw('1 = 0');
            }

            if ($user->hasRole('Bank Pusat')) {
                // Bank Pusat bisa melihat semua data
                return $query;
            } elseif ($user->hasRole('Bank Unit')) {
                // Bank Unit bisa melihat data kecuali milik Bank Pusat
                return $query->whereHas('bankUnit', function (Builder $query) {
                    $query->where('name', '=', 'name');
                });
            } else {
                // Peran lain hanya bisa melihat data mereka sendiri
                return $query->where('user_id', $user->id);
            }
        });
            // ->query(
            //     // ...
            // )
        // ->columns([
        //     Tables\Columns\TextColumn::make('name')
        //         ->searchable(),
        //     Tables\Columns\TextColumn::make('email')
        //         ->label('Email')
        //         ->searchable(),
        //     Tables\Columns\TextColumn::make('bank_unit')
        //         ->searchable()
        //         ->Label('Bank Unit Terdaftar'),
        //     Tables\Columns\TextColumn::make('alamat')
        //         ->searchable(),
        //     Tables\Columns\TextColumn::make('no_hp')
        //         ->searchable(),
        // ]);
    }
}
