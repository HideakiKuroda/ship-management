<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ship;

class Ship_owner extends Model
{
    use HasFactory;

    protected $fillable = [
       'owner_name',
       'ratio',
       'ship_id'
        
    ];

    public function ships(): BelongsTo
    {
        return $this->belongsTo(Ship::class);
    }
}
