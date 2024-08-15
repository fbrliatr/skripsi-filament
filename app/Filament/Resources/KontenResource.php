<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KontenResource\Pages;
use App\Filament\Resources\KontenResource\RelationManagers;
use App\Models\Konten;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KontenResource extends Resource
{
    protected static ?string $model = Konten::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    public static function getPluralModelLabel(): string
    {
        return 'Daftar Konten'; // Set the plural label to be the same as the singular label
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Author')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('title')
                ->label('Judul')
                ->required()
                ->maxLength(255),
            Forms\Components\DatePicker::make('tgl_rilis')
                ->label('Tanggal Rilis')
                ->date()
                ->required(),
            Forms\Components\MarkdownEditor::make('description')
                ->label('Deskripsi Konten')
                ->required()
                ->maxLength(5000)
                ->columnSpan('full'),
            Forms\Components\FileUpload::make('image')
                ->label('Upload Gambar')
                ->required()
                ->image()
                ->columnSpan('full')
                ->disk('public'),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_rilis')
                    ->label('Tanggal Rilis')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->markdown(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->date()
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
            'index' => Pages\ListKontens::route('/'),
            'create' => Pages\CreateKonten::route('/create'),
            'view' => Pages\ViewKonten::route('/{record}'),
            'edit' => Pages\EditKonten::route('/{record}/edit'),
        ];
    }
}
