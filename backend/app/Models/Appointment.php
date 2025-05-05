<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'pet_id',
        'veterinarian_id',
        'scheduled_at',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function veterinarian()
    {
        return $this->belongsTo(Veterinarian::class);
    }

    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'appointment_service',
            'appointment_id',
            'service_id'
        );
    }
}
