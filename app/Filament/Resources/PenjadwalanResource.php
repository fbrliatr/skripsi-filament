<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\BankUnit;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Penjadwalan;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
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
    protected static ?string $navigationGroup = 'Unit Penjadwalan';
    protected static ?string $navigationLabel= 'Penjadwalan';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

    public static function getPluralModelLabel(): string
    {
        $user = Auth::user();
            if ($user->hasAnyRole('Bank Pusat')) {
                return 'Penjadwalan'; // Set the plural label to be the same as the singular label
            }
            else {
                // Jika tidak ada pengguna yang login, tidak mengembalikan apapun
                return 'Penjadwalan';
            }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('bank_unit_id')
                //     ->relationship('bankUnit', 'name')
                //     ->required(),
                // Forms\Components\DatePicker::make('tgl_angkut')
                //     ->date()
                //     ->required(),
                // Forms\Components\DatePicker::make('tanggal')
                //     ->label('Tanggal Permintaan')
                //     ->date()
                //     ->required(),
            ]);
    }

    public static function table(Table $table): Table
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
                }
                elseif ($user->hasRole('Bank Unit')) {
                    // $query->whereHas('bankUnit', function (Builder $query) use ($user) {
                    // Bank Unit bisa melihat data kecuali milik Bank Pusat
                        return $query->where('id_bank_unit', $user->bank_unit);
                    // });
                } else {
                    // Peran lain hanya bisa melihat data mereka sendiri
                    return $query->where('user_id', $user->id);
                }
            })
            ->modelLabel('Daftar Jadwal Permintaan')
            ->columns([
                Tables\Columns\TextColumn::make('id_bank_unit')
                    ->label('Bank Unit')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_angkut')
                    ->label('Jam')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('H:i') : 'N/A'), // Format jam dan menit
                Tables\Columns\TextColumn::make('transaksi.status')
                    ->label('Status')
                    ->sortable()
                    ->searchable()

                // Tables\Columns\TextColumn::make('tgl_angkut')
                //     ->default('0000-00-00')
                //     ->sortable(),
                ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
