<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Ship;
use App\Models\Pro_category;
use App\Models\Project;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'ship_id',
        'interim_start1',
        'interim_dline1',
        'Periodic_start1',
        'Periodic_dline1',
        'interim_start2',
        'interim_dline2',
        'Periodic_start2',
        'Periodic_dline2',
       ];



       public function ships(): BelongsTo
       {
           return $this->belongsTo(Ship::class);
       }

}
