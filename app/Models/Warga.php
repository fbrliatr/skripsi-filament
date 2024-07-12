<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warga extends Model
{
    use HasFactory, HasRoles;


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

    public function transaksi(): HasMany
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
    // public function roles() {
    //     return $this->belongsToMany(Role::class);
    // }
//     public function transaksi(): BelongsTo
//     {
//         return $this->belongsTo(Transaksi::class);
//     }
}
