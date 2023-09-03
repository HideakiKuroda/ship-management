<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ship;
use App\Models\Ship_attachment;
use SpatiePermissionVue\Traits\RolesPermissionsToVue;
use App\Models\User_description;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RolesPermissionsToVue;
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function projects(): BelongsToMany
    {
    return $this->belongsToMany(Project::class, 'pro_assignments');
    }

    public function Tasks(): BelongsToMany
    {
    return $this->belongsToMany(Task::class, 'task_assignments');
    }

    public function user_descriptions(): HasOne
    {
        return $this->hasOne(User_description::class);
    }

    public function ships(): BelongsToMany
    {
        return $this->belongsToMany(Ship::class, 'ship_assignments');
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'dept_assignments');
    }
    public function isAdmin()
    {
        return $this->hasRole('admin'); // Spatie Permission パッケージの hasRole を使う場合
    }

    
}


