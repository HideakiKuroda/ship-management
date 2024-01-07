<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Model_has_role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];

    public function users():BelongsTo
    {
    return $this->belongsTo(User::class,'id','model_id');
    }
}
