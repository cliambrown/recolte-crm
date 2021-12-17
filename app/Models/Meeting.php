<?php

namespace App\Models;

use App\Enums\MeetingType;
use App\Traits\HasLoggedUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Meeting extends Model
{
    use HasFactory;
    use HasLoggedUser;
    use SoftDeletes;
    use Searchable;
    
    protected $casts = [
        'type' => MeetingType::class,
        'occurred_on' => 'date',
        'occurred_on_datetime' => 'datetime',
    ];
    
    public function toSearchableArray()
    {
        $array = [];
        $array['id'] = $this->id;
        $attributes = [
            'name',
            'description',
        ];
        foreach ($attributes as $attr) {
            $value = remove_accents($this->$attr);
            $array[$attr] = $value;
        }
        return $array;
    }
    
    public function getDateStrAttribute() {
        if ($this->occurred_on_datetime) {
            return $this->occurred_on_datetime->isoFormat('LLL');
        }
        return $this->occurred_on->isoFormat('LL');
    }
}
