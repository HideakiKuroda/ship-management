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
        'pro_category_id',
        'category_no',
        'project_id',
        'legal_start',
        'legal_dline',
        'start_date',
        'end_date',
         ];


    public function pro_categories():BelongsTo
    {
        return $this->belongsTo(Pro_category::class, 'pro_category_id');
    }
    public function ships():BelongsTo
    {
        return $this->belongsTo(Ship::class, 'ship_id');
    }
    public function projects(): HasOne
    {
        return $this->hasOne(Project::class, 'project_id');
    }

}
