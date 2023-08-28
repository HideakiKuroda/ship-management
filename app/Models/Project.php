<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pro_attachment;
use App\Models\Pro_category;
use App\Models\Ship;
Use App\Models\Task;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function users()
    {
    return $this->belongsToMany(User::class, 'pro_assignments');
    }

    public function pro_attachments()
    {
        return $this->hasMany(Pro_attachment::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function pro_categories()
    {
        return $this->belongsTo(Pro_category::class);
    }

    public function ships()
    {
        return $this->belongsTo(Ship::class);
    }
}


