<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return $this->succes($categories);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name"=> "requried|string",
            "description" => 'nullable',
        ]);

        $category = Category::create([
            'name'=> $data['name'],
            'slug' => Str::slug($data['name']),
            'description'=> $data['description'],
        ]);
        
        return $this->succes($category, 'Created successfully', 201);
    }

    public function show(Category $category)
    {
        return $this->succes($category);
    }

    public function update(Request $request, Category $category)
    {
        $category->update($request->all());
        return $this->succes($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->succes(null, 'deleted successfully');
    }

}
