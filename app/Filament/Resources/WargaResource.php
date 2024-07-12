<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Warga;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TransaksiWarga;
use PhpParser\Node\Stmt\Label;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\WargaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WargaResource\RelationManagers;
use Spatie\Permission\Traits\HasRoles;

class WargaResource extends Resource
{
    protected static ?string $model = Warga::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel= 'Daftar Warga';
    protected static ?string $modelLabel= 'Daftar Warga';
    protected static ?string $navigationGroup= 'Unit Administratif';
    protected static ?int $navigationSort= 2;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user','email')
                    ->required()
                    ->Label('Email'),
                Forms\Components\TextInput::make('name')
                    // ->relationship('user', 'name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                // Forms\Components\TextInput::make('email')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('password')
                //     ->required()
                //     ->maxLength(255),
                Forms\Components\Select::make('bank_unit')
                    ->label('Bank Unit Terdaftar')
                    ->options(['Bank Unit 1' => 'Bank Unit 1',
                    'Bank Unit 2' => 'Bank Unit 2',
                    'Bank Unit 3' => 'Bank Unit 3',
                    'Bank Unit 4' => 'Bank Unit 4',
                    'Bank Unit 5' => 'Bank Unit 5',
                    'Bank Unit 6' => 'Bank Unit 6',
                    'Bank Unit 7' => 'Bank Unit 7',
                    'Bank Unit 8' => 'Bank Unit 8'])
                    ->required(),
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_unit')
                    ->searchable()
                    ->Label('Bank Unit Terdaftar'),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_berat')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn (Warga $record): string => number_format($record->totalBerat(), 2).' kg'),
                Tables\Columns\TextColumn::make('total_pendapatan')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn (Warga $record): string => 'Rp'.number_format($record->totalTransaksiPrice(), 2)),
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
    }public static function getPluralModelLabel(): string
    {
        return 'Daftar Warga'; // Set the plural label to be the same as the singular label
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWargas::route('/'),
            'create' => Pages\CreateWarga::route('/create'),
            'view' => Pages\ViewWarga::route('/{record}'),
            'edit' => Pages\EditWarga::route('/{record}/edit'),
        ];
    }
}
