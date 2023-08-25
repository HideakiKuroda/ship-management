<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User_description;

class Department extends Model
{
    use HasFactory;

    public function user_descriptions()
        {
        return $this->belongsToMany(User_description::class, 'dept_assignments');
        }

}
