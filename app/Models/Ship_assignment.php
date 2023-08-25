<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Ship;

class Ship_assignment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ship_id', 'role_id'];

    public function ships(): HasMany
    {
        return $this->hasMany(Ship::class);
    }
    
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
