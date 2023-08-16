<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Tsk_attachment;


class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'project_id', 'parent_id'];  // 必要なフィールドを追加してください。

    public function projects()
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

    public function Tsk_attachments()
    {
        return $this->hasMany(Tsk_attachment::class);
    }

    public function users()
    {
    return $this->belongsToMany(User::class, 'Task_assignments');
    }
}
