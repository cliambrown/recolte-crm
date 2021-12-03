<?php

namespace App\Models;

use App\Traits\HasLoggedUser;
use App\Traits\HasStartEndDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPerson extends Model
{
    use HasFactory;
    use HasLoggedUser;
    use HasStartEndDate;
    
    public function project() {
        return $this->belongsTo(Project::class);
    }
    
    public function person() {
        return $this->belongsTo(Person::class);
    }
}
