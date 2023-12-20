<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ship;

class Operat_section extends Model
{
    use HasFactory;

    public function ships(): BelongsTo
    {
        return $this->belongsTo(Ship::class);
    }
}
