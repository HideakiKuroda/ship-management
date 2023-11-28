<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Task;
use App\Models\User;

class Task_attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'filename',
        'title',
        'originname',
        'icon',
        'user_id'
     ];
    public function tasks(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function users(): BelongsTo
    {
    return $this->belongsTo(User::class, 'user_id','id');
    }

}
