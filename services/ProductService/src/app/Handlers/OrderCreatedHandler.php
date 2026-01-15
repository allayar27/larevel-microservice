<?php

namespace App\Handlers;

use App\Events\OrderCreatedEvent;
use App\Models\Product;

final class OrderCreatedHandler
{
    public function handle($payload): void
    {
        $data = $payload['data'];
        $product = Product::find($data['product_id']);

        if (!$product) {
            throw new \RuntimeException('Product not found');
        }

        $product->decrement('count', $data['count']);
    }
}
