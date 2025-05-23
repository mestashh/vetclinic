<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];


    public function appointments()
    {
        return $this->belongsToMany(
            Appointment::class,
            'appointment_service',
            'service_id',
            'appointment_id'
        );
    }
    public function items()
    {
        return $this->hasMany(ServiceItem::class);
    }
}
