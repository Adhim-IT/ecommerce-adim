<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'stripe_payment_id',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'stripe_payment_id' => 'string',
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderitems()
    {
        return $this->hasMany(OrderItem::class);
    }
}