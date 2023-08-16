<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Ship;

class Concerned extends Model
{
    use HasFactory;

    public function ships()
    {
        return $this->belongsTo(Ship::class);
    }
}
