<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class Role extends Model
{
    // Relasi many-to-many dengan model User
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}


