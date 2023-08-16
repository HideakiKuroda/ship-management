<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Ship_owner;
use App\Models\Navigation_area;
use App\Models\Operat_section;
use App\Models\Summary;
use App\Models\Summary2;
use App\Models\Concerned;

class Ship extends Model
{
    use HasFactory;

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function ship_owners()
    {
        return $this->hasMany(Ship_owner::class);
    }

    public function navigation_areas()
    {
        return $this->belongsTo(Navigation_area::class);
    }

    public function operat_sections()
    {
        return $this->belongsTo(Operat_section::class);
    }

    public function summary2s()
    {
        return $this->hasOne(Summary2::class);
    }

    public function summaries()
    {
        return $this->hasOne(Summary::class);
    }

    public function concerneds()
    {
        return $this->hasOne(Concerned::class);
    }
}
