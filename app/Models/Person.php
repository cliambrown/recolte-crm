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
}
