<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return $this->succes($products);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $product = Product::query()->create([
            "title"=> $data["title"],
            "article" => $data["article"],
            "slug" => Str::slug($data["title"]),
            "description"=> $data["description"] ?? null,
            "price" => $data["price"],
            "count" => $data["count"],
        ]);
        return $this->succes($product, 'product created successfully', 201);
    }

    public function show (Product $product)
    {
        return $this->succes($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return $this->succes(null, 'product deleted successfully');
    }
}
