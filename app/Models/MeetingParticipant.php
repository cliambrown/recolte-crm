<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $fillable = ['meeting_id','person_id'];
    
    protected static function booted() {
        static::deleting(function ($participant) {
            $participant->orgs()->detach();
        });
    }
    
    public function person() {
        return $this->belongsTo(Person::class);
    }
    
    public function orgs() {
        return $this->belongsToMany(Org::class, 'meeting_participant_org');
    }
}
