<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepositoryInterface;
use App\Services\CurrencyService;
use App\Services\ServiceService;
use App\Services\CategoryService;
use App\DTO\CreateProductDTO;

class ProductsController extends Controller
{
    protected $productRepository;
    protected $categoryService;
    protected $serviceService;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryService $categoryService,
        ServiceService $serviceService
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryService = $categoryService;
        $this->serviceService = $serviceService;
    }

    public function index(ProductFilter $filter)
    {
        $products = $this->productRepository->getAllWithFilter($filter)->appends(request()->all());
        $categories = $this->categoryService->getAll();

        $currencyService = new CurrencyService();

        foreach ($products as $product) {
            $product->price_usd = $currencyService->convert($product->price, 'USD');
            $product->price_eur = $currencyService->convert($product->price, 'EUR');
            $product->price_rub = $currencyService->convert($product->price, 'RUB');
        }

        return view('index', compact('products', 'categories'));
    }

    public function show($product_id)
    {
        $product = $this->productRepository->findById($product_id);
        $category = $product->categories->first();
        $services = $this->serviceService->getAll();

        return view('product', compact('product', 'category', 'services'));
    }

    public function store(StoreProductRequest $request)
    {
        $dto = new CreateProductDTO($request->validated());
        $product = $this->productRepository->create($dto->toArray());
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    public function update(UpdateProductRequest $request, $product_id)
    {
        $dto = new CreateProductDTO($request->validated());
        $product = $this->productRepository->update($product_id, $dto->toArray());
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
