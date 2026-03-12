<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Get the parent order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product details for this item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
