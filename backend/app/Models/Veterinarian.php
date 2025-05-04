<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'specialization',
        'phone',
        'email',
    ];

    /**
     * У ветеринара может быть несколько приёмов
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
