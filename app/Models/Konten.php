<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'konten';

    protected $fillable = [
            'name',
            'title',
            'description',
            'image_path',
            'image',
            'tgl_rilis',
    ];
        /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'title' => 'string',
        'tgl_rilis' => 'datetime',
        'description' => 'string',
        'image_path' => 'string',
        'image' => 'string',
    ];

    // public static function boot()
    // {
    //     parent::boot();

    //     static::saving(function ($model) {
    //         request()->validate([
    //             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         ]);
    //     });
    // }
}

