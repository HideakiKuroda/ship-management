<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Ship;

class Summary extends Model
{
    use HasFactory;

    protected $fillable = [
        'aux_engine',
        'beam_depth',
        'breadth',
        'dk_machine_pp',
        'draft_m',
        'draft_mark_A',
        'draft_mark_F',
        'engine_kw',
        'exhaust',
        'fire_extinguish',
        'fm_bl',
        'full_length',
        'harbor_gen',
        'intake',
        'layer_2or3',
        'lpp',
        'me_model',
        'me_sno',
        'mold_draft',
        'official_number',
        'pera_sno',
        'pera_spec',
        'signal_code',
        'stern_towboat',
        'winch_tension',
           
    ];


    public function ships(): BelongsTo
    {
        return $this->belongsTo(Ship::class);
    }
}
