<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Color;

class Navigation_area extends Model
{
    use HasFactory;

    public function colors()
    {
        return $this->belongsTo(Color::class);
    }
}
