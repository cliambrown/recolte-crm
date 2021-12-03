<?php

namespace App\Models;

use App\Traits\HasAddress;
use App\Traits\HasLoggedUser;
use App\Traits\HasPhone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Org extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use HasPhone;
    use HasAddress;
    use HasLoggedUser;
    
    protected $appends = ['name_with_short_name'];
    
    public function toSearchableArray()
    {
        $array = [];
        $array['id'] = $this->id;
        $attributes = [
            'name',
            'short_name',
            'website',
        ];
        foreach ($attributes as $attr) {
            $value = remove_accents($this->$attr);
            $array[$attr] = $value;
        }
        return $array;
    }
    
    protected static function booted() {
        static::deleting(function ($org) {
            $org->positions()->delete();
            $org->parent_relationships()->delete();
            $org->child_relationships()->delete();
        });
    }
    
    // Returns "name (short_name)"
    public function getNameWithShortNameAttribute() {
        $fullName = $this->name;
        if ($this->short_name) {
            $fullName .= ' ('.$this->short_name.')';
        }
        return $fullName;
    }
    
    public function types() {
        return $this->belongsToMany(OrgType::class, 'org_org_types');
    }
    
    public function getTypeIdsAttribute() {
        return $this->types->pluck('id')->toArray();
    }
    public function getTypeNamesAttribute() {
        return $this->types->pluck('name')->toArray();
    }
    
    public function positions() {
        return $this->hasMany(Position::class)
            ->orderByRaw('ISNULL(end_year) DESC')
            ->orderBy('end_year', 'desc')
            ->orderByRaw('ISNULL(end_month) DESC')
            ->orderBy('end_month', 'desc')
            ->orderByRaw('ISNULL(end_day) DESC')
            ->orderBy('end_day', 'desc')
            ->orderByRaw('ISNULL(start_year) DESC')
            ->orderBy('start_year', 'desc')
            ->orderByRaw('ISNULL(start_month) DESC')
            ->orderBy('start_month', 'desc')
            ->orderByRaw('ISNULL(start_day) DESC')
            ->orderBy('start_day', 'desc');
    }
    
    public function parent_relationships() {
        return $this->hasMany(OrgRelationship::class, 'parent_org_id')
            ->orderByRaw('ISNULL(end_year) DESC')
            ->orderBy('end_year', 'desc')
            ->orderByRaw('ISNULL(end_month) DESC')
            ->orderBy('end_month', 'desc')
            ->orderByRaw('ISNULL(end_day) DESC')
            ->orderBy('end_day', 'desc')
            ->orderByRaw('ISNULL(start_year) DESC')
            ->orderBy('start_year', 'desc')
            ->orderByRaw('ISNULL(start_month) DESC')
            ->orderBy('start_month', 'desc')
            ->orderByRaw('ISNULL(start_day) DESC')
            ->orderBy('start_day', 'desc');
    }
    
    public function child_relationships() {
        return $this->hasMany(OrgRelationship::class, 'child_org_id')
            ->orderByRaw('ISNULL(end_year) DESC')
            ->orderBy('end_year', 'desc')
            ->orderByRaw('ISNULL(end_month) DESC')
            ->orderBy('end_month', 'desc')
            ->orderByRaw('ISNULL(end_day) DESC')
            ->orderBy('end_day', 'desc')
            ->orderByRaw('ISNULL(start_year) DESC')
            ->orderBy('start_year', 'desc')
            ->orderByRaw('ISNULL(start_month) DESC')
            ->orderBy('start_month', 'desc')
            ->orderByRaw('ISNULL(start_day) DESC')
            ->orderBy('start_day', 'desc');
    }
}
