<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductsController extends Controller
{
    public function index(ProductFilter $filter)
    {
        $products = Product::filter($filter)->get();
        $categories = Category::all();

        return view('index', compact('products', 'categories'));
    }

    public function show($product_id)
    {
        $product = Product::with('categories')->find($product_id);
        $category = $product->categories->first();
        return view('product', compact('product', 'category'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        $product = Product::create($validated);
        $product->categories()->attach($request->category_id);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    public function update(UpdateProductRequest $request, $product_id)
    {
        $validated = $request->validated();

        $product = Product::find($product_id);
        $product->update($validated);
        $product->categories()->sync([$request->category_id]);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    public function destroy($product_id)
    {
        $product = Product::find($product_id);
        $product->categories()->detach();
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
