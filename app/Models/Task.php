<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Tsk_attachment;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'project_id', 'parent_id'];  // 必要なフィールドを追加してください。

    public function projects():BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent():BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function Tsk_attachments(): HasMany
    {
        return $this->hasMany(Tsk_attachment::class);
    }

    public function users(): BelongsToMany
    {
    return $this->belongsToMany(User::class, 'Task_assignments');
    }
}
