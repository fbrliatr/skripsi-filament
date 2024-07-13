<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\BankUnit;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Penjadwalan;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PenjadwalanResource\Pages;
use App\Filament\Resources\PenjadwalanResource\Widgets\Rute;
use App\Filament\Resources\PenjadwalanResource\RelationManagers;

class PenjadwalanResource extends Resource
{
    protected static ?string $model = Penjadwalan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // public static function getItemsRepeater(): Repeater
    // {
    //     return Repeater::make('penjadwalan')
    //         ->relationship('penjadwalan')
    //         ->schema([
    //             Forms\Components\Select::make('id_bank_unit')
    //                 ->label('Bank Unit')
    //                 ->relationship('bank_units', 'name')
    //                 ->required(),

    //             Forms\Components\TextInput::make('tanggal')
    //                 ->label('Berat')
    //                 ->numeric()
    //                 ->live(true)
    //                 ->required(),
    //                 // ->afterStateUp
    //         ]);
    // }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('bank_unit_id')
                    ->relationship('bankUnit', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_angkut')
                    ->date()
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal Permintaan')
                    ->date()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_bank_unit')
                    ->label('Bank Unit')
                    ->prefix('Bank Unit '),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_angkut')
                    ->label('Jam')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('H:i') : 'N/A'), // Format jam dan menit
                Tables\Columns\TextColumn::make('tgl_angkut')
                    ->default('0000-00-00')
                    ->sortable(),
                ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets(): array
    {
        return [
            Rute::Class
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjadwalans::route('/'),
            'create' => Pages\CreatePenjadwalan::route('/create'),
            'view' => Pages\ViewPenjadwalan::route('/{record}'),
            'edit' => Pages\EditPenjadwalan::route('/{record}/edit'),
        ];
    }
}
