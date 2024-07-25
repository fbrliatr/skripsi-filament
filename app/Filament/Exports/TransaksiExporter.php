<?php

namespace App\Filament\Exports;

use App\Models\Transaksi;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TransaksiExporter extends Exporter
{
    protected static ?string $model = Transaksi::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('code'),
            ExportColumn::make('bank_unit_name'),
            ExportColumn::make('tanggal'),
            ExportColumn::make('jam_angkut'),
            ExportColumn::make('kategori'),
            ExportColumn::make('total_berat')
            ->getStateUsing(fn($record) => $record->totalBerat() . ' kg'),
            ExportColumn::make('total_harga')
            ->getStateUsing(fn($record) => 'Rp' . number_format($record->totalHarga(),2)),
            ExportColumn::make('status'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),

        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your transaksi export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
