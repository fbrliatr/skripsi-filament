<?php

namespace App\Models;

use App\Models\Warga;
use App\Models\BankUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DaftarRute extends Model
{
    use HasFactory;

    protected $table = 'daftar_rute'; // Pastikan nama tabel sesuai

    protected $fillable = [
        'route',
        'tgl_angkut',
        'jam_angkut',
        'jarak',
        // 'urutan',
    ];
    public function bankUnit(): BelongsTo
    {
        return $this->belongsTo(BankUnit::class);
    }

    public function penjadwalan(): BelongsTo
    {
        return $this->belongsTo(Penjadwalan::class);
    }

}
