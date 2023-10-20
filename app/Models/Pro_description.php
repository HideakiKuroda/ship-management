<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Project;
use App\Models\User;

class Pro_description extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'project_id','memo'];


    public function projects(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function users()
    {
    return $this->belongsTo(User::class, 'user_id');
    }

}
