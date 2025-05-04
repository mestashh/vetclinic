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

    /**
     * Приём принадлежит клиенту
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Приём принадлежит питомцу
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Приём ведёт конкретный ветеринар
     */
    public function veterinarian()
    {
        return $this->belongsTo(Veterinarian::class);
    }

    /**
     * Связь many-to-many с услугами через pivot appointment_service
     */
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
