<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankUnitResource\Pages;
use App\Filament\Resources\BankUnitResource\RelationManagers;
use App\Models\BankUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankUnitResource extends Resource
{
    protected static ?string $model = BankUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel= 'Daftar Bank Unit';
    protected static ?string $modelLabel= 'Daftar Bank Unit';
    protected static ?string $navigationGroup= 'Unit Administratif';
    protected static ?int $navigationSort= 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->label('Pengelola')
                    ->relationship('user','name')
                    ->required(),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengelola')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable()


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
