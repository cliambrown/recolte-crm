<?php

namespace App\Models;

use App\Traits\HasLoggedUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgRelationship extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasLoggedUser;
    
    protected $fillable = [
        'created_by_user_id',
        'updated_by_user_id',
    ];
}
