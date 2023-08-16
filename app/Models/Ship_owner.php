<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ship;

class Ship_owner extends Model
{
    use HasFactory;

    public function ships()
    {
        return $this->belongsTo(Ship::class);
    }
}
