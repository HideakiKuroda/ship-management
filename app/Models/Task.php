<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Task_attachment;


class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'project_id', 'parent_id'];  // 必要なフィールドを追加してください。

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function Task_attachments()
    {
        return $this->hasMany(Task_attachment::class);
    }

    public function users()
    {
    return $this->belongsToMany(User::class, 'Tsk_assignments');
    }
}
