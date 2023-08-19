<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Ship;

class Summary extends Model
{
    use HasFactory;

    public function ships(): BelongsTo
    {
        return $this->belongsTo(Ship::class);
    }
}
