<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TransaksiWarga;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\TransaksiWargaExporter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiWargaResource\Pages;
use App\Filament\Resources\TransaksiWargaResource\RelationManagers;

class TransaksiWargaResource extends Resource
{
    protected static ?string $model = TransaksiWarga::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Transaksi Warga';
    protected static ?string $modelLabel = 'Transaksi Warga';
    protected static ?string $navigationGroup = 'Daftar Transaksi';
    protected static ?int $navigationSort= 2;

    public static function getPluralModelLabel(): string
    {
        $user = Auth::user();
            if ($user->hasAnyRole('Bank Unit','Bank Pusat')) {
                // Jika tidak ada pengguna yang login, tidak mengembalikan apapun
                return 'Daftar Transaksi Warga';
            }

            else {
                return 'Riwayat Transaksi'; // Set the plural label to be the same as the singular label
            }
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('transaksi_id')
                    ->relationship('transaksi', 'id')
                    ->required(),
                Forms\Components\Select::make('warga_id')
                    ->relationship('warga', 'name')
                    ->required(),
                Forms\Components\TextInput::make('berat')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
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
            } elseif ($user->hasRole('Bank Unit')) {
                // Bank Unit bisa melihat data kecuali milik Bank Pusat
                $query->whereHas('warga', function (Builder $query) use ($user) {
                    return $query->where('bank_unit', $user->bank_unit);
            });
            } else {
                // Peran lain hanya bisa melihat data mereka sendiri
                return $query->whereHas('warga', function (Builder $query){
                    $user = Auth::user();
                    $query->where('user_id', $user->id);
                });
            }
        })
            ->columns([
                Tables\Columns\TextColumn::make('transaksi.code')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('warga.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('warga.bank_unit')
                    ->label('Bank Unit')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('berat')
                    ->numeric()
                    ->suffix(' kg')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->numeric()
                    ->prefix('Rp')
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaksi.tanggal')
                    ->label('Tanggal Transaksi')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('transaksi.status')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->colors([
                        'success' => fn ($state) => $state === 'Requested',
                        'danger' => fn ($state) => $state === 'Ditolak',
                        'warning' => fn ($state) => $state === 'Menunggu',

                    ]),
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
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                ->visible(fn () => Auth::user()->hasRole(['Bank Unit', 'Bank Pusat'])),
            ])
            ->headerActions([
                ExportAction::make()->exporter(TransaksiWargaExporter::class)
            ])
            ->recordUrl(function ($record) {
                // if ($record->trashed()){
                //     return null;
                // }
                return Pages\ViewTransaksiWarga::getUrl([$record->id]);
            })
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksiWargas::route('/'),
            'create' => Pages\CreateTransaksiWarga::route('/create'),
            'view' => Pages\ViewTransaksiWarga::route('/{record}'),
            'edit' => Pages\EditTransaksiWarga::route('/{record}/edit'),
        ];
    }
}
