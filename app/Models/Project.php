<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Pro_attachment;
// use App\Models\Pro_assignment;
use App\Models\Pro_category;
use App\Models\Ship;
Use App\Models\Task;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Validation\Rules\Exists;

class Project extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'ship_id',
        'name',
        'pro_category_id',
        'start_date',
        'end_date',
        'completion',
   ];

    protected $dates = ['deleted_at'];

    public function users():BelongsToMany
    {
    return $this->belongsToMany(User::class, 'pro_assignments');
    }

    public function pro_attachments():HasMany
    {
        return $this->hasMany(Pro_attachment::class);
    }

    public function tasks():HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function pro_categories():BelongsTo
    {
        return $this->belongsTo(Pro_category::class, 'pro_category_id');
    }

    public function ships():BelongsTo
    {
        return $this->belongsTo(Ship::class, 'ship_id');
    }


//scopeの設定

    //完了・未完了の設定
    public function scopeEndOrNoProject($query,$EndOrNo=0) //0:未完了　1:完了
    {
        if($EndOrNo === 0){
           return $query->whereNull('completion');
        }
        else{
            return $query->whereNotNull('completion');
         }
    }  
    //船選択
    public function scopeShipProject($query,$shipId=null)
    {
        if(!empty($shipId)){
           return $query->where('ship_id','=',$shipId);
        }
    }  
    //作成日の日付範囲で検索
    public function scopeDateCreateProject($query,$crtDate=null,$crtAddDate)
    {
        if(!empty($crtDate)){
           return $query->whereBetween('created_at',[$crtDate,$crtAddDate]);
        }
    
    }
    //完了日日付範囲で検索
    public function scopeDateEndProject($query,$endDate=null,$endAddDate)
    {
        if(!empty($maxDate)){
           return $query->whereBetween('completion',[$endDate,$endAddDate]);
        }
    
    }
    //ユーザーで検索
    public function scopeUserProject($query, $userId = null)
    {
        if (!empty($userId)) {
            return $query->whereHas('users', function ($query) use ($userId) {
                $query->where('pro_assignments.user_id', $userId);
            });
        }
    }
    
}


