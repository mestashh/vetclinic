<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'breed',
        'birth_date',
        'client_id',
    ];

    /**
     * Каждый питомец принадлежит одному клиенту
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * У питомца может быть несколько приёмов
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
