<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Person extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    
    public function toSearchableArray()
    {
        $array = [];
        $array['id'] = $this->id;
        $attributes = [
            'given_name',
            'family_name',
            'notes',
            'org_names',
        ];
        foreach ($attributes as $attr) {
            $value = remove_accents($this->$attr);
            $array[$attr] = $value;
        }
        return $array;
    }
    
    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
    
    public function getDisplayGivenNameAttribute() {
        $gn = $this->given_name;
        if (!$gn || (gettype($gn) === 'string' && !trim($gn))) {
            return '[?]';
        }
        return $gn;
    }
    
    public function getDisplayFamilyNameAttribute() {
        $fn = $this->family_name;
        if (!$fn || (gettype($fn) === 'string' && !trim($fn))) {
            return '[?]';
        }
        return $fn;
    }
    
    public function orgs() {
        return $this->belongsToMany(Org::class)->using(Position::class);
    }
    
    public function getOrgNamesAttribute() {
        // return $this->orgs->pluck('')
    }
}
