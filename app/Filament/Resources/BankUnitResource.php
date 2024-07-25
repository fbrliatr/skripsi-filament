<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankUnitResource\Pages;
use App\Filament\Resources\BankUnitResource\RelationManagers;
use App\Models\BankUnit;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankUnitResource extends Resource
{
    protected static ?string $model = BankUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel= 'Daftar Bank Unit';
    protected static ?string $modelLabel= 'Daftar Bank Unit';
    protected static ?string $navigationGroup= 'Unit Administratif';
    protected static ?int $navigationSort= 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->default(function () {
                        $latestTransaksi = BankUnit::latest()->first();
                        return str_pad($latestTransaksi ? $latestTransaksi->id + 2 : 2, 6, '0', STR_PAD_LEFT);
                    })
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(32),
                    // ->unique(BankUnit::class, 'id', ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pengelola')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_hp')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                // Forms\Components\Select::make('user_id')
                //     ->label('Pengelola')
                //     ->options(function () {
                //         return User::whereHas('roles', function ($query) {
                //                 $query->where('name', 'admin');
                //             });
                //         })
                //     ->required(),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jarak')
                    ->label('Jarak ke Bank Pusat')
                    ->required()
                    ->postfix('km'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pengelola')
                    ->label('Pengelola')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. Handphone')
                    ->searchable()
                    ->prefix('0'),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jarak')
                    ->searchable()
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
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
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
        return 'Daftar Bank Unit'; // Set the plural label to be the same as the singular label
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBankUnits::route('/'),
            'create' => Pages\CreateBankUnit::route('/create'),
            'view' => Pages\ViewBankUnit::route('/{record}'),
            'edit' => Pages\EditBankUnit::route('/{record}/edit'),
        ];
    }
}
