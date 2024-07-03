<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    protected $fillable = [
        'family_id',
        'hobby'
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
