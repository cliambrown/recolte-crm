<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Casts\NullableBoolean;
use App\Traits\HasLoggedUser;
use App\Traits\HasPhone;
use App\Traits\HasStartEndDate;

class Position extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasPhone;
    use HasLoggedUser;
    use HasStartEndDate;
    
    protected $casts = [
        'is_current' => NullableBoolean::class,
    ];
    
    protected $fillable = [
        'is_current',
    ];
    
    public function org() {
        return $this->belongsTo(Org::class);
    }
    
    public function person() {
        return $this->belongsTo(Person::class);
    }
}
