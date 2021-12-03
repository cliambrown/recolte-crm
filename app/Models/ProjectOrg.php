<?php

namespace App\Models;

use App\Traits\HasLoggedUser;
use App\Traits\HasStartEndDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectOrg extends Model
{
    use HasFactory;
    use HasLoggedUser;
    use HasStartEndDate;
    
    public function project() {
        return $this->belongsTo(Project::class);
    }
    
    public function org() {
        return $this->belongsTo(Org::class);
    }
}
