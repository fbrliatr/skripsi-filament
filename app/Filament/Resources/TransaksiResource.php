<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
use App\Models\Warga;
use App\Models\TransaksiWarga;
use Filament\Forms;
use Filament\Tables;
use App\Models\Warga;
use Filament\Forms\Form;
use App\Models\Transaksi;
use App\Enums\OrderStatus;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\TransaksiWarga;
use Tables\Columns\TimeColumn;
use Filament\Resources\Resource;
use Filament\Forms\ComponentGroup;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Pages\Actions\EditAction;
use Filament\Pages\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\TransaksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiResource\RelationManagers;




class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationLabel = 'Daftar Transaksi';
    protected static ?string $modelLabel = 'Daftar Transaksi';
    protected static ?string $navigationGroup = 'Unit Keuangan';
    protected static ?int $navigationSort = 1;

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
                    ->columnSpan(['lg' => fn(?Transaksi $record) => $record === null ? 3 : 2]),
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
                Tables\Columns\TextColumn::make('total_berat')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->totalBerat() . ' kg'),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn($record) => 'Rp' . $record->totalPengeluaran()),
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
            // Forms\Components\TextInput::make('status')
            //     ->required()
            //     ->maxLength(255),
            Forms\Components\DatePicker::make('tanggal')
                ->date()
                ->required(),
            Forms\Components\TimePicker::make('jam_angkut')
                ->label('Jam Angkut')
                ->displayFormat('H:i') // Menampilkan jam dan menit
                ->format('H:i')
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
