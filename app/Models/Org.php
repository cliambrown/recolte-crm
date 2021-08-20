<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Org extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    
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
