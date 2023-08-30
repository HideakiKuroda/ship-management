<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Ship;

class Ship_attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ship_id',
        'filename',
        'title',
        'originname',
        'icon'
     ];

    public function ships(): BelongsTo
    {
        return $this->belongsTo(Ship::class);
    }
}
