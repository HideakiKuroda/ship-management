<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ship;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Summary2 extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_type',
        'international_ton',
        'passenger_capacity',
        'rpm_peller100',
        'rpm_peller50',
        'slip_rate100',
        'slip_rate50',
        'speed100',
        'speed50',
        'tug_force100',
        'tug_force50',
          
    ];


    public function ships() : BelongsTo
    {
        return $this->belongsTo(Ship::class);
    }
}
