<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRead extends Model
{
    protected $table = 'products_read';

    protected $primaryKey = 'product_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = ['product_id', 'name', 'slug', 'price', 'count', 'article', 'description'];
    public $timestamps = false;
}
