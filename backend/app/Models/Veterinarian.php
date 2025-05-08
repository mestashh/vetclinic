<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialization',
        'phone',
        'email',
        'user_id'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

}
