<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'shipping_address',
    ];

    /**
     * Get the buyer (User) who placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the individual items within this order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}