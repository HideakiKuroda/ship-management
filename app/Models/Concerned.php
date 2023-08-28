<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Ship;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Concerned extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower',
        'crew_arrange',
        'manager',
        'operator',
         
    ];

    public function ships():BelongsTo
    {
        return $this->belongsTo(Ship::class);
    }
}
