<?php

namespace App\Handlers;

use App\Events\OrderCreatedEvent;
use App\Models\Product;

final class OrderCreatedHandler
{
    public function handle($payload): void
    {
        $product = Product::find($payload['product_id']);

        if (!$product) {
            throw new \RuntimeException('Product not found');
        }

        $product->decrement('inventory', $payload['count']);
    }
}
