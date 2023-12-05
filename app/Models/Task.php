<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Task_attachment;
use App\Models\Task_description;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;


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
        'end_date',
	    'deadline',
	    'completion',
        'priority',
     ];


    public function projects():BelongsTo
    {
        return $this->belongsTo(Project::class,'project_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function task_attachments(): HasMany
    {
        return $this->hasMany(Task_attachment::class);
    }

    public function task_descriptions():HasMany
    {
        return $this->hasMany(Task_description::class,'task_id');
    }

    //scopeの設定

    //ユーザーで検索
    public function scopeUserTask($query, $userId = null)
    {
        if (!empty($userId)) {
            return $query->whereHas('projects.users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            });
        }
        return $query;
    }
    //船選択
    public function scopeShipTask($query,$shipId=null)
    {
        if (!empty($shipId)) {
            // Task と Project とのリレーションを使用
            return $query->whereHas('projects', function ($query) use ($shipId) {
                // Project と Ship とのリレーションを使用
                $query->where('ship_id', $shipId);
            });
        }
        return $query;
    }  
    //完了・未完了の設定
    public function scopeEndOrNoTask($query,$EndOrNo=0) //0:未完了　1:完了 2:全体  
     {
        if($EndOrNo == 0){
           return $query->whereNull('completion');
        }
        elseif ($EndOrNo == 1) {
            return $query->whereNotNull('completion');
         }
         elseif ($EndOrNo == 2) {
            return $query;
        } 
        return $query;
    }  
    //作成日の日付範囲で検索
    public function scopeDateCreateTask($query,$crtDate,$crtAddDate)
    {
        if(!empty($crtDate)){
           return $query->whereBetween("created_at",[$crtDate,$crtAddDate]);
        }
        return $query;
    
    }
    //完了日日付範囲で検索
    public function scopeDateEndTask($query,$endDate,$endAddDate)
    {
        if(!empty($endDate)){
           return $query->whereBetween("completion",[$endDate,$endAddDate]);
        }
        return $query;
    }
    
}


   
