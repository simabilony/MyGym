<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'user_id', 'payment_method', 'status', 'payment status',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest Customer'
        ]);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id', 'id', 'id')
            ->using(OrderItem::class)
            ->as('order_item')
            ->withPivot([
                'product_name', 'price', 'quantity', 'options',
            ]);
    }
public function items()
{
    return $this->hasMany(OrderItem::class,'order_id');
}
    public function addresses()
    {
        return $this->hasMany(OrderAddress::class);
    }
    public function billingAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id')
            ->where('type', '=', 'billing');
//return $this->addresses()->where('type', '', 'billing");
    }
}

