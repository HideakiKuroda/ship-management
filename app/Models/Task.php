<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Task_attachment;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Task extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'project_id',
        'parent_id',
        'name',
        'color_id',
        'ganttchart',
        'start_date',
        'end_date',
	    'deadline',
	    'completion',
     ];


    public function projects():BelongsTo
    {
        return $this->belongsTo(Project::class,'project_id');
    }

    public function parent():BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function task_attachments(): HasMany
    {
        return $this->hasMany(Task_attachment::class);
    }

    public function users(): BelongsToMany
    {
    return $this->belongsToMany(User::class, 'Task_assignments');
    }
}
