<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'birthdate',
        'mobile_no',
        'address',
        'state_id',
        'city_id',
        'pincode',
        'marital_status',
        'wedding_date',
        'hobbies',
        'photo'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function members()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function hobbies()
    {
        return $this->hasMany(Hobby::class);
    }

}
