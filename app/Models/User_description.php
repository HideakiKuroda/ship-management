<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\User;

class User_description extends Model
{
    use HasFactory;

    public function departments()
    {
    return $this->belongsToMany(Department::class, 'dept_assignments');
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}
