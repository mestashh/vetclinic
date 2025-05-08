<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'pet_id',
        'veterinarian_id',
        'scheduled_at',
        'status',
    ];

    // Связь «один ко многим» с клиентом
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Связь «один ко многим» с питомцем
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    // Связь «один ко многим» с ветеринаром
    public function veterinarian(): BelongsTo
    {
        return $this->belongsTo(Veterinarian::class);
    }

    // Связь «многие ко многим» с услугами через таблицу appointment_service
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'appointment_service');
    }
}
