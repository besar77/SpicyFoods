<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function deliveryArea()
    // {
    //     return $this->belongsTo(DeliveryArea::class);
    // }

    public function userAddress()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
