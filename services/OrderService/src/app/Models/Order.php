<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'count',
        'user_id',
        'status',
        'total_price',
        'address'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(UserRead::class, 'user_id', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductRead::class, 'product_id', 'product_id');
    }
}
