<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    
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
}
