<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Project;
use App\Models\Ship_owner;
use App\Models\Navigation_area;
use App\Models\Operat_section;
use App\Models\Summary;
use App\Models\Summary2;
use App\Models\Concerned;
use App\Models\User;
use App\Models\Ship_attachment;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ship extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'delivered',
        'ex_name',
        'former_name',
        'gross_tonn',
        'name',
        'operat_section_id',
        'navigation_area_id',
        'ship_no',
        'yard',
        'issueInspCert',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function ship_owners(): HasMany
    {
        return $this->hasMany(Ship_owner::class);
    }

    public function navigation_areas(): BelongsTo
    {
        return $this->belongsTo(Navigation_area::class, 'navigation_area_id');
    }

    public function operat_sections(): BelongsTo
    {
        return $this->belongsTo(Operat_section::class, 'operat_section_id');
    }

    public function summary2s(): HasOne
    {
        return $this->hasOne(Summary2::class);
    }

    public function summaries(): HasOne
    {
        return $this->hasOne(Summary::class);
    }

    public function concerneds(): HasOne
    {
        return $this->hasOne(Concerned::class);
    }

    public function users(): BelongsToMany
    {
    return $this->belongsToMany(User::class, 'ship_assignments');
    }

    public function ship_attachments(): HasMany
    {
        return $this->hasMany(Ship_attachment::class);
    }
}
