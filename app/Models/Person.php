<?php

namespace App\Models;

use App\Traits\HasAddress;
use App\Traits\HasPhone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Person extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use HasPhone;
    use HasAddress;
    
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
    
    public function positions() {
        return $this->hasMany(Position::class)
            ->orderBy('is_current', 'desc')
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
    
    public function current_position() {
        return $this->hasOne(Position::class)
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
            ->orderBy('start_day', 'desc')
            ->where('is_current', 1);
    }
    
    public function getCurrentPosition() {
        if ($this->relationLoaded('positions')) {
            return $this->positions->firstWhere('is_current');
        }
        return $this->current_position;
    }
    
    public function getCurrentEmailAttribute() {
        if (optional($this->getCurrentPosition())->email) {
            return $this->getCurrentPosition()->email;
        }
        return $this->email;
    }
    
    public function getCurrentReadablePhoneAttribute() {
        if (optional($this->getCurrentPosition())->readable_phone) {
            return $this->getCurrentPosition()->readable_phone;
        }
        return $this->readable_phone;
    }
}
