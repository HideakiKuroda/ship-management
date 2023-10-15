<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Project;
use App\Models\User;


class Pro_attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'filename',
        'title',
        'originname',
        'icon',
        'user_id'
     ];
     
     public function projects(): BelongsTo
     {
         return $this->belongsTo(Project::class, 'project_id','id');
     }
 
     public function users()
     {
     return $this->belongsTo(User::class, 'user_id','id');
     }
 
}
