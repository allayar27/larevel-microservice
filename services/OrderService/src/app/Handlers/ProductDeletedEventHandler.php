<?php

namespace App\Handlers;

use App\Models\ProductRead;
use Illuminate\Support\Facades\Log;

final class ProductDeletedEventHandler
{
    public function handle($payload): void
    {
        try {
            ProductRead::query()->where('product_id',$payload['data']['id'])->delete();
        } catch (\Throwable $e) {
            Log::error('failed delete ' . $e->getMessage());
        }
    }
}
