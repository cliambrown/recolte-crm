<?php

namespace App\Models;

use App\Traits\HasLoggedUser;
use App\Traits\HasStartEndDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgRelationship extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasLoggedUser;
    use HasStartEndDate;
    
    protected $fillable = [
        'created_by_user_id',
        'updated_by_user_id',
    ];
    
    public function parent_org() {
        return $this->belongsTo(Org::class);
    }
    
    public function child_org() {
        return $this->belongsTo(Org::class);
    }
}
