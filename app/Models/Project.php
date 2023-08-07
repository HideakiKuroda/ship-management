<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pro_attachment;

class Project extends Model
{
    use HasFactory;

    public function users()
    {
    return $this->belongsToMany(User::class, 'pro_assignments');
    }

    public function Pro_attachments()
    {
        return $this->hasMany(Pro_attachment::class);
    }
}


