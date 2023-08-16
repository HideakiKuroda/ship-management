<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ship;

class Summary2 extends Model
{
    use HasFactory;

    public function ships()
    {
        return $this->belongsTo(Ship::class);
    }
}
