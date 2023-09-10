<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Project;


class Pro_assignment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'project_id'];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'project_id');
    }
    
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'user_id','id');
    }
}
