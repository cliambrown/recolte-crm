<?php

namespace App\Traits;

trait HasAddress
{
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
}