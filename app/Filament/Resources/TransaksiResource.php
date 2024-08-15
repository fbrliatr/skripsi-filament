<?php

namespace App\Filament\Resources;

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
use Filament\Actions\ActionGroup;
use Filament\Forms\ComponentGroup;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
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
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\TransaksiExporter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\TransaksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiResource\RelationManagers;




class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationLabel = 'Transaksi Bank Unit';
    protected static ?string $modelLabel = 'Transaksi Bank Unit';
    protected static ?string $navigationGroup = 'Daftar Transaksi';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $requestCount = static::getModel()::where('status', 'Requested')->count();

        return $requestCount > 0 ? (string) $requestCount : null;

    }
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
                    // $query->whereHas('transaksi', function (Builder $query) use ($user) {
                    // Bank Unit bisa melihat data kecuali milik Bank Pusat
                        return $query->where('bank_unit_name', $user->bank_unit);
                    // });
                } else {
                    // Peran lain hanya bisa melihat data mereka sendiri
                    return $query->where('user_id', $user->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank_unit_name')
                    ->label('Nama Bank Unit')
                    ->sortable()
                    ->searchable(),
                    // ->getStateUsing(fn($record) => $record->bankUnit()),

                    // ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jam_angkut')
                    ->label('Jam')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('H:i') : 'N/A'), // Format jam dan menit
                Tables\Columns\BadgeColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->colors([
                        'warning' => fn ($state) => $state === 'Menunggu' or $state === 'Requested' or $state === 'Dalam Perjalanan',
                        'danger' => fn ($state) => $state === 'Ditolak',
                    ]),
                Tables\Columns\TextColumn::make('kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_berat')
                    ->numeric()
                    ->getStateUsing(fn($record) => $record->totalBerat() . ' kg'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn($record) => 'Rp' . number_format($record->totalHarga(),2)),
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
                Filter::make('created_at')
                ->form([
                    DatePicker::make('tanggal'),
                    DatePicker::make('created_until')
                        ->default(now()),
                    ])->columns(2)
                ->columnSpanFull()
            ], layout: FiltersLayout::AboveContent)

            ->actions([
                Tables\Actions\ViewAction::make('view')
                    ->visible(function () {
                        return auth()->user()->hasAnyRole('Bank Pusat','Bank Unit');
                    }),
                    // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Edit Status')
                    ->icon('heroicon-m-pencil-square')
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Requested' => 'Requested',
                                'Diterima' => 'Diterima',
                                'Menunggu' => 'Menunggu',
                                'Dalam Perjalanan' => 'Dalam Perjalanan',
                                'Selesai' =>'Selesai',
                                'Ditolak'=> 'Ditolak',
                            ])
                            ->default(function (Transaksi $transaksi) {
                                $status = null;
                                if ($transaksi->status == 'Requested') {
                                    $status='Requested';
                                }
                                elseif ($transaksi->status == 'Diterima') {
                                    $status='Diterima';
                                }
                                elseif ($transaksi->status == 'Menunggu') {
                                    $status='Menunggu';
                                }
                                elseif ($transaksi->status == 'Dalam Perjalanan') {
                                    $status='Dalam Perjalanan';
                                }
                                elseif ($transaksi->status == 'Selesai') {
                                    $status='Selesai';
                                }
                                else {
                                    $status='Ditolak';
                                }
                                return $status;
                            }),
                        ])
                    ->action(function (Transaksi $transaksi, array $data): void {
                        $transaksi->status = $data['status'];
                        $transaksi->save();

                        Notification::make()
                            ->title('Status Transaksi Telah Diperbaharui')
                            ->success()
                            ->send();
                    })
                    ->visible(function () {
                        return auth()->user()->hasRole('Bank Pusat');
                    }),
                ])
            ->headerActions([
                ExportAction::make()->exporter(TransaksiExporter::class)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
        return 'Daftar Transaksi Bank Unit'; // Set the plural label to be the same as the singular label
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
            Forms\Components\TextInput::make('bank_unit_name')
                ->default(function () {
                    $user = Auth::user();
                    return $user ? $user->bank_unit : null;
                })
                ->dehydrated()
                ->label('Bank Unit')
                // ->disabled()
                // ->relationship('bankUnit', 'name')
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
                ->default('Requested')
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
            // Toggle::make('auto_generate')
            //     ->autofocus() // Autofocus the field.
            //     ->inline() // Render the toggle inline with its label.
            //     ->offIcon('') // Set the icon that should be displayed when the toggle is off.
            //     ->onIcon('') // Set the icon that should be displayed when the toggle is on.
            //     // ->stacked(), // Render the toggle under its label.
        ];
    }
    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('transaksiWargas')
            ->relationship('transaksiWargas')
            ->label('Transaksi Warga')
            ->schema([
                Forms\Components\Select::make('warga_id')
                    ->label('Warga')
                    ->relationship('warga', 'name')
                    ->options(function () {
                        $user = Auth::user();

                        if ($user && $user->hasRole('Bank Unit')) {
                            // Membatasi opsi berdasarkan bank unit pengguna yang sedang login
                            return Warga::where('bank_unit', $user->bank_unit)
                                ->pluck('name', 'id')
                                ->toArray();
                        }
                        else {
                            return Warga::all()->pluck('name','id');
                        }
                    })
                    ->required(),

                Forms\Components\TextInput::make('berat')
                    ->label('Berat')
                    ->numeric()
                    ->live(true)
                    ->required(),
                    // ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                    //     if ($operation !== 'create') {
                    //         return;
                    //     }
                    //     $berat = filter_var($state['berat'], FILTER_VALIDATE_INT);
                    //     $price = $berat * 5000;

                    //     // Set nilai 'price' dengan harga yang dihitung
                    //     $set('price', $price);
                    // }),
                Forms\Components\TextInput::make('price')
                    ->label('Total')
                    ->prefix('Rp')
                    ->numeric()
                    // ->disabled(),
            ])
            ->columns(3);
    }
}
