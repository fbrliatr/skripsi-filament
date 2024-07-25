<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\FormsComponent;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'Unit Administratif';

    protected static ?int $navigationSort= 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_hp')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('roles_id')
                    ->label('Pilih Role')
                    ->required()
                    ->relationship('roles', 'name'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->maxLength(255),
                Forms\Components\Select::make('bank_unit')
                    ->searchable()
                    ->options(['Bank Unit 1' => 'Bank Unit 1',
                               'Bank Unit 2' => 'Bank Unit 2',
                               'Bank Unit 3' => 'Bank Unit 3',
                               'Bank Unit 4' => 'Bank Unit 4',
                               'Bank Unit 5' => 'Bank Unit 5',
                               'Bank Unit 6' => 'Bank Unit 6'])
                    ->Label('Bank Unit Terdaftar'),
                Forms\Components\TextArea::make('alamat')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('bank_unit')
                    ->searchable()
                    ->Label('Bank Unit Terdaftar'),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable()
                    ->Label('Alamat'),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
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
                Tables\Actions\DeleteAction::make(),
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
            //         // Super admin bisa melihat semua pengguna
            //         return $query;
            //     } elseif ($user->hasRole('Bank Unit')) {
            //         // Admin bisa melihat pengguna yang bukan super admin
            //         return $query->whereHas('roles', function ($query) {
            //             $query->where('name', '!=', 'Bank Pusat');
            //         });
            //     } else {
            //         // Peran lain hanya bisa melihat diri mereka sendiri
            //         return $query->where('id', $user->id);
            //     }
            // });
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
