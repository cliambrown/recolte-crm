<?php

namespace App\Models;

use App\Traits\HasAddress;
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
    
    protected $appends = ['name_with_short_name'];
    
    public function toSearchableArray()
    {
        $array = [];
        $array['id'] = $this->id;
        $attributes = [
            'name',
            'short_name',
            'website',
            // 'org_names',
        ];
        foreach ($attributes as $attr) {
            $value = remove_accents($this->$attr);
            $array[$attr] = $value;
        }
        return $array;
    }
    
    // Returns "name (short_name)", mostly for searching
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
    
    public function people() {
        return $this->belongsToMany(Person::class, 'positions')->using(Position::class);
    }
}
