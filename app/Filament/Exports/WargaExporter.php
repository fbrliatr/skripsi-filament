<?php

namespace App\Filament\Exports;

use App\Models\Warga;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class WargaExporter extends Exporter
{
    protected static ?string $model = Warga::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('No.'),
            ExportColumn::make('name'),
            ExportColumn::make('bank_unit'),
            ExportColumn::make('email'),
            ExportColumn::make('no_hp'),
            ExportColumn::make('alamat'),
            ExportColumn::make('total_berat')
                ->getStateUsing(fn (Warga $record): string => number_format($record->totalBerat(), 2).' kg'),
            ExportColumn::make('total_pendapatan')
                ->getStateUsing(fn (Warga $record): string => 'Rp'.number_format($record->totalTransaksiPrice(), 2)),

            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your Data warga export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
