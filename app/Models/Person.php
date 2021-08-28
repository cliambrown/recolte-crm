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
    
    protected $appends = ['full_name'];
    
    public function toSearchableArray()
    {
        $array = [];
        $array['id'] = $this->id;
        $attributes = [
            'given_name',
            'family_name',
            // 'org_names',
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
    
    public function getFullNameAttribute() {
        return $this->display_given_name.' '.$this->display_family_name;
    }
    
    public function setPhoneAttribute($value) {
        $phoneObj = get_valid_phone_obj($value);
        $phone = get_readable_phone($phoneObj);
        $this->attributes['phone'] = $phone;
    }
    
    public function getReadablePhoneAttribute() {
        $phoneObj = get_valid_phone_obj($this->phone);
        if (!$phoneObj) return null;
        return get_readable_phone($phoneObj);
    }
    
    public function setPostalCodeAttribute($value) {
        if (!is_string($value)) $value = null;
        if (!$value) $value = null;
        else {
            $pc = strtoupper($value);
            $pc = preg_replace('/[^A-Z0-9]/', '', $pc);
            if (preg_match('/^([A-Z][0-9][A-Z])([0-9][A-Z][0-9])$/', $pc, $matches) === 1) {
                $value = $matches[1].' '.$matches[2];
            } else {
                $value = null;
            }
        }
        $this->attributes['postal_code'] = $value;
    }
    
    public function getOneLineAddressAttribute() {
        $parts = [];
        if ($this->street_address) $parts[] = $this->street_address;
        if ($this->street_address_2) $parts[] = $this->street_address_2;
        if ($this->city) $parts[] = $this->city;
        if ($this->province) $parts[] = $this->province;
        if ($this->country) $parts[] = $this->country;
        if ($this->postal_code) $parts[] = $this->postal_code;
        return implode(', ', $parts);
    }
    
    public function positions() {
        return $this->hasMany(Position::class)
            ->orderBy('start_year', 'desc')
            ->orderBy('start_month', 'desc')
            ->orderBy('start_day', 'desc')
            ->orderBy('end_year', 'desc')
            ->orderBy('end_month', 'desc')
            ->orderBy('end_day', 'desc');
    }
    
    public function current_position() {
        return $this->hasMany(Position::class)
            ->where('is_current', 1)
            ->orderBy('start_year', 'desc')
            ->orderBy('start_month', 'desc')
            ->orderBy('start_day', 'desc')
            ->orderBy('end_year', 'desc')
            ->orderBy('end_month', 'desc')
            ->orderBy('end_day', 'desc')
            ->first();
            
    }
    
    public function getCurrentPositionAttribute() {
        if ($this->relationLoaded('positions')) {
            return $this->positions->firstWhere('is_current');
        }
        return $this->positions()
            ->where('is_current', 1)
            ->first();
    }
    
    // public function getOrgNamesAttribute() {
    //     $names = $this->orgs->pluck('name', 'short_name');
    //     dd($names);
    //     return $names;
    // }
}
