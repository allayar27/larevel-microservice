<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\RabbitMQService;

class ProductObserver
{
    public function __construct(protected RabbitMQService $service) { }

    public function created(Product $product): void
    {
        $product->refresh();

        $this->service->publish(
            'product.created',
            [
                'id'    => $product->id,
                'title'  => $product->title,
                'description' => $product->description,
                'article'  => $product->article,
                'slug' => $product->slug,
                'price' => $product->price,
                'count' => $product->count
            ],
        );
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $product->refresh();

        $this->service->publish(
            'product.updated',
            [
                'id'    => $product->id,
                'title'  => $product->title,
                'description' => $product->description,
                'article'  => $product->article,
                'slug' => $product->slug,
                'price' => $product->price,
                'count' => $product->count
            ],
        );
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->service->publish(
            'product.deleted',
            [
                'id'    => $product->id,
                'title'  => $product->title,
                'description' => $product->description,
                'article'  => $product->article,
                'slug' => $product->slug,
                'price' => $product->price,
                'count' => $product->count
            ],
        );
    }
}
