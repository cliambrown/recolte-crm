<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasPhone
{
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
    
    // Intended for use in search indices
    public function getSpacedPhoneAttribute() {
        $phoneStr = $this->readable_phone;
        if (!$phoneStr) return null;
        if (Str::startsWith($phoneStr, '+1')) $phoneStr = substr($phoneStr, 2);
        return trim(preg_replace('/[^0-9]/', ' ', $phoneStr));
    }
    
    public function getCompressedPhoneAttribute() {
        $phoneStr = $this->spaced_phone;
        if (!$phoneStr) return null;
        return trim(str_replace(' ', '', $phoneStr));
    }
}