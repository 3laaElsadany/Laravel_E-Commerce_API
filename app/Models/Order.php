<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'location_id',
        'status',
        'total_price',
        'date_of_delivery'
    ];

    // Define relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define relationship with Location model
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    // Define relationship with OrderItem model
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
