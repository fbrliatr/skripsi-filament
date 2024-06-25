<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'bank_unit_id',
        'transaksi_id',
        'name',
        'alamat',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'bank_unit_id' => 'integer',
        'transaksi_id' => 'integer',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
    public function transaksiWargas(): HasMany
    {
        return $this->hasMany(TransaksiWarga::class);
    }
    public function totalTransaksiPrice(): float
    {
        return $this->transaksiWargas()->sum('price');
    }
    public function totalBerat(): float
    {
        return $this->transaksiWargas()->sum('berat');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bankUnit(): BelongsTo
    {
        return $this->belongsTo(BankUnit::class);
    }

//     public function transaksi(): BelongsTo
//     {
//         return $this->belongsTo(Transaksi::class);
//     }
}
