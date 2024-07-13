<?php

namespace App\Models;

use App\Models\Warga;
use App\Models\BankUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjadwalan extends Model
{
    use HasFactory;

    protected $table = 'penjadwalan'; // Pastikan nama tabel sesuai
    public function bankUnit(): BelongsTo
    {
        return $this->belongsTo(BankUnit::class, 'id_bank_unit');
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

}
