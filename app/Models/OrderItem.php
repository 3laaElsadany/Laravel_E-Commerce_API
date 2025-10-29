<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    // Define relationship with Order model
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Define relationship with Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
