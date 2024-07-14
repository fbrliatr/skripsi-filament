<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Warga;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TransaksiWarga;
use PhpParser\Node\Stmt\Label;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Spatie\Permission\Traits\HasRoles;
use Filament\Infolists\Components\Grid;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\WargaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WargaResource\RelationManagers;
use App\Filament\Resources\WargaResource\Widgets\DaftarTransaksi;


class WargaResource extends Resource
{
    protected static ?string $model = Warga::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel= 'Daftar Warga';
    protected static ?string $modelLabel= 'Daftar Warga';
    protected static ?string $navigationGroup= 'Unit Administratif';
    protected static ?int $navigationSort= 2;

    public static function tableQuery(): Builder
    {
        $user = Auth::user();

        // Hanya tampilkan data sesuai peran pengguna yang sedang login
        if ($user->hasRole('Bank Pusat')) {
            return parent::tableQuery();
        } elseif ($user->hasRole('Bank Unit')) {
            return parent::tableQuery()->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'Bank Pusat');
            });
        } else {
            return parent::tableQuery()->where('id', $user->id);
        }
    }

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
                    return $query->whereHas('roles', function (Builder $query) {
                        $query->where('name', '!=', 'Bank Pusat');
                    });
                } else {
                    // Peran lain hanya bisa melihat data mereka sendiri
                    return $query->where('user_id', $user->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_unit')
                    ->searchable()
                    ->Label('Bank Unit Terdaftar'),
                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
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
            // ->modifyQueryUsing(function (Builder $query) {
            //     $user = Auth::user();
            //     if (!$user) {
            //         // Jika tidak ada pengguna yang login, tidak mengembalikan apapun
            //         return $query->whereRaw('1 = 0');
            //     }

            //     if ($user->hasRole('Bank Pusat')) {
            //         // Bank Pusat bisa melihat semua pengguna
            //         return $query;
            //     } elseif ($user->hasRole('Bank Unit')) {
            //         // Bank Unit bisa melihat semua pengguna kecuali yang memiliki peran 'Bank Pusat'
            //         return $query->whereHas('users', function ($query) {
            //             $user=Auth::user();
            //             $query->where('bank_unit', $user->id);
            //         });
            //     } else {
            //         // Peran lain hanya bisa melihat diri mereka sendiri
            //         return $query->where('id', $user->id);
            //     }
            // });


    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            // ->can(Tables\Infolist::class);
            ->schema([
                // Grid::make('2') // Create a grid with 2 columns
                // ->schema([
                    Section::make('Data Diri')
                        ->schema([
                            Grid::make(3)
                            ->schema([
                                TextEntry::make('name')
                                ->label('Nama Lengkap'),
                                TextEntry::make('bank_unit')
                                ->label('Bank Unit Terdaftar'),

                                TextEntry::make('no_hp')
                                ->label('No. Telepon')
                                ->prefix('0'),
                                TextEntry::make('alamat'),
                                TextEntry::make('email'),
                            ]),
                        ]),
                    Section::make('Pendapatan')
                        ->schema([
                            Grid::make()
                            ->schema([
                                TextEntry::make('total_berat')
                                    ->getStateUsing(fn (Warga $record): string => number_format($record->totalBerat(), 2).' kg'),
                                TextEntry::make('total_pendapatan')
                                    ->getStateUsing(fn (Warga $record): string => 'Rp'.number_format($record->totalTransaksiPrice(), 2,',')),
                            ]),
                        ]),
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

    public static function getWidgets(): array
    {
        return [
            DaftarTransaksi::Class
        ];
    }
}
