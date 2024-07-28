<?php

namespace App\Filament\Exports;

use App\Models\Transaksi;
use App\Models\TransaksiWarga;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class TransaksiWargaExporter extends Exporter
{
    protected static ?string $model = TransaksiWarga::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('Kode')
                ->label('Kode Transaksi')
                ->state(fn(TransaksiWarga $record):string=> $record->transaksi->code),
            ExportColumn::make('warga')
                ->state(fn(TransaksiWarga $record):string=> $record->warga->name),
            ExportColumn::make('bank_Unit')
                ->state(fn(TransaksiWarga $record):string=> $record->warga->bank_unit),
            ExportColumn::make('berat')
                ->suffix('kg'),
            ExportColumn::make('price')
                ->label('Harga')
                ->prefix('Rp'),
            ExportColumn::make('status')
            ->state(fn(TransaksiWarga $record):string=> $record->transaksi->status),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your transaksi warga export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
