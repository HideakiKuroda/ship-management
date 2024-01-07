<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User_description extends Model
{
    use HasFactory;

    public function departments():BelongsToMany
    {
    return $this->belongsToMany(Department::class, 'dept_assignments');
    }

    public function users():BelongsTo
    {
    return $this->belongsTo(User::class);
    }

}
