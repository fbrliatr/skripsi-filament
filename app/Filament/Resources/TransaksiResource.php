<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
use App\Models\Warga;
use App\Models\TransaksiWarga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;




class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel= 'Daftar Transaksi';
    protected static ?string $modelLabel= 'Daftar Transaksi';
    protected static ?string $navigationGroup= 'Unit Keuangan';
    protected static ?int $navigationSort= 1;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                        ->schema(static::getDetailsFormSchema())
                        ->columns(2),
                    Forms\Components\Section::make('Transaksi Warga')
                        ->schema([
                            static::getItemsRepeater(),
                        ])
                ])
                ->columnSpan(['lg' => fn (?Transaksi $record) => $record === null ? 3 : 2]),
        ]);

    }

                // Forms\Components\Textarea::make('description')
                //     ->required()
                //     ->columnSpanFull(),



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bankUnit.name')
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
                Tables\Columns\TextColumn::make('warga.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('berat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getPluralModelLabel(): string
    {
        return 'Daftar Transaksi'; // Set the plural label to be the same as the singular label
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksi::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'view' => Pages\ViewTransaksi::route('/{record}'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('code')
                ->default(function () {
                    $latestTransaksi = Transaksi::latest()->first();
                    return 'TXN-' . str_pad($latestTransaksi ? $latestTransaksi->id + 1 : 1, 6, '0', STR_PAD_LEFT);
                })
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(Transaksi::class, 'code', ignoreRecord: true),
            Forms\Components\Select::make('bank_unit_id')
                ->relationship('bankUnit', 'name')
                ->required(),
            Forms\Components\TextInput::make('kategori')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('status')
                ->required()
                ->maxLength(255),
            Forms\Components\DatePicker::make('tanggal')
                ->date()
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'Requested' => 'Requested',
                    'Diterima' => 'Diterima',
                    'Menunggu' => 'Menunggu',
                    'Dalam Perjalanan' => 'Dalam Perjalanan',
                    'Selesai' =>'Selesai',
                    'Ditolak'=> 'Ditolak',
                ])
                ->required(),
            // Forms\Components\TextInput::make('price')
            //     ->required()
            //     ->numeric()
            //     ->default(5000)
            //     ->prefix('$'),

                ];
    }
    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('transaksiWargas')
            ->relationship('transaksiWargas')
            ->schema([
                Forms\Components\Select::make('warga_id')
                    ->label('Warga')
                    ->relationship('warga', 'name')
                    ->required(),

                Forms\Components\TextInput::make('berat')
                    ->label('Berat')
                    ->numeric()
                    ->default(1)
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->label('Total')
                    ->dehydrated()
                    ->numeric()
                    ->required(),
            ])
            ->columns(3);
    }
}
