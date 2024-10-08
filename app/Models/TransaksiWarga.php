<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiWarga extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $table = 'transaksi_wargas'; // Pastikan nama tabel sesuai

    protected $fillable = [
        // 'transaksi_id',
        'warga_id',
        'berat',
        'price',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function bankUnit(): BelongsTo
    {
        return $this->belongsTo(BankUnit::class);
    }

}
