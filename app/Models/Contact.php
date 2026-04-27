<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['user_id', 'contact_id', 'name'];

    public function contactUser()
    {
        return $this->belongsTo(User::class, 'contact_id');
    }

    // Display name: custom name > contact's real name > phone
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: $this->contactUser->name;
    }
}
