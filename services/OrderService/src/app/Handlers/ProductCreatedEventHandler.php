<?php

namespace App\Handlers;

use App\Models\ProductRead;
use Illuminate\Support\Facades\Log;

final class ProductCreatedEventHandler
{
    public function handle($payload): void
    {
        $data = $payload['data'];

        try {

            ProductRead::updateOrCreate(
            ['product_id' => $data['id']],
            [
                    'product_id' => $data['id'],
                    'name'  => $data['title'],
                    'slug' => $data['slug'],
                    'price'  => $data['price'],
                    'article' => $data['article'],
                    'count' => $data['count'],
                    'description' => $data['description']
                ]
            );

        } catch (\Throwable $e) {
            Log::error('failed create user ' . $e->getMessage());
        }
    }
}
