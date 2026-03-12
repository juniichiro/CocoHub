<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'age',
        'address',
        'phone_number',
        'profile_picture', 
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} " . ($this->middle_name ? "{$this->middle_name} " : "") . "{$this->last_name}";
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
