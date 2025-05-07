<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = [
        'name',
        'species',
        'breed',
        'age',
        'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
