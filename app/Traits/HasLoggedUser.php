<?php

namespace App\Traits;

use App\Models\User;

trait HasLoggedUser
{
    
    public static function bootHasLoggedUser() {
        
        static::created(function ($model) {
            $userID = optional(auth()->user())->id;
            $model::withoutEvents(function () use ($model, $userID) {
                $model->update([
                    'created_by_user_id' => $userID,
                ]);
            });
        });
        
        static::updated(function ($model) {
            $userID = optional(auth()->user())->id;
            $model::withoutEvents(function () use ($model, $userID) {
                $model->update([
                    'updated_by_user_id' => $userID,
                ]);
            });
        });
    }
    
    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
    
    public function getCreatedByUserNameAttribute() {
        if (!optional($this->created_by_user)->name) return '['.__('deleted').']';
        return $this->created_by_user->name;
    }
    
    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
    
    public function getUpdatedByUserNameAttribute() {
        if (!optional($this->updated_by_user)->name) return '['.__('deleted').']';
        return $this->updated_by_user->name;
    }
}