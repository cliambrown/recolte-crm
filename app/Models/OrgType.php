<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgType extends Model
{
    public $timestamps = false;
    
    public function orgs() {
        return $this->belongsToMany(Org::class, 'org_org_types');
    }
}
