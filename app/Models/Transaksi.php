<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'transaksi'; // Pastikan nama tabel sesuai

    protected $fillable = [
        'code',
        'bank_unit_id',
        'warga_id',
        'berat',
        'kategori',
        'status',
        'tanggal',
        'price',
        'warga_bank_unit_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bank_unit_id' => 'integer',
        'warga_id' => 'integer',
        'tanggal' => 'datetime',
        'price' => 'integer',
        'warga_bank_unit_id' => 'integer',
    ];

    public function transaksiWargas(): hasMany
    {
        return $this->hasMany(TransaksiWarga::class);
    }
    public function totalBerat(): float
    {
        return $this->transaksiWargas()->sum('berat');
    }

    public function totalPengeluaran(): float
    {
        return $this->transaksiWargas()->sum('price');
    }

    public function bankUnit(): BelongsTo
    {
        return $this->belongsTo(BankUnit::class);
    }

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }
}
