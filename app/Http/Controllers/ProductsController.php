<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepositoryInterface;

class ProductsController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(ProductFilter $filter)
    {
        $products = $this->productRepository->getAllWithFilter($filter);
        $categories = Category::all();

        return view('index', compact('products', 'categories'));
    }

    public function show($product_id)
    {
        $product = $this->productRepository->findById($product_id);
        $category = $product->categories->first();
        return view('product', compact('product', 'category'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $product = $this->productRepository->create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    public function update(UpdateProductRequest $request, $product_id)
    {
        $validated = $request->validated();
        $product = $this->productRepository->update($product_id, $validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    public function destroy($product_id)
    {
        $this->productRepository->delete($product_id);

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
