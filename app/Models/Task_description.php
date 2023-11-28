<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Task;
use App\Models\User;

class Task_description extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'task_id','memo'];


    public function tasks(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function users(): BelongsTo
    {
    return $this->belongsTo(User::class, 'user_id');
    }

}
