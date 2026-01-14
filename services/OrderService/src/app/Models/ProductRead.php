<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRead extends Model
{
    protected $table = 'products_read';
    protected $fillable = ['product_id', 'title', 'slug', 'price', 'article', 'inventory', 'description'];
    public $timestamps = false;
}
