<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Color;


class Pro_category extends Model
{
    use HasFactory;

    protected $fillable = [
        
    ];


    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function colors()
    {
        return $this->belongsTo(Color::class);
    }
}
