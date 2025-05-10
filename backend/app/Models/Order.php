<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_item_id',
        'quantity',
        'comment',
        'price',
    ];

    public function item()
    {
        return $this->belongsTo(ServiceItem::class, 'service_item_id');
    }
}
