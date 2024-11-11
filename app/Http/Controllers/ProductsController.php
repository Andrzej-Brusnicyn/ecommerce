<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Service;
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
        $products = $this->productRepository->getAllWithFilter($filter)->appends(request()->all());
        $categories = Category::all();

        return view('index', compact('products', 'categories'));
    }

    public function show($product_id)
    {
        $product = $this->productRepository->findById($product_id);
        $category = $product->categories->first();
        $services = Service::all();

        return view('product', compact('product', 'category', 'services'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productRepository->create($request->validated());

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    public function update(UpdateProductRequest $request, $product_id)
    {
        $product = $this->productRepository->update($product_id, $request->validated());

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

### https://medium.com/@soulaimaneyh/laravel-repository-pattern-da4e1e3efc01
