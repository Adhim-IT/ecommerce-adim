<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\OrderItem;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function cartitems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function orderitems()
    {
        return $this->hasMany(OrderItem::class);
    }
}