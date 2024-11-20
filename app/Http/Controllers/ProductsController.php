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
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProductsController extends Controller
{
    protected ProductRepositoryInterface $productRepository;
    protected CategoryService $categoryService;
    protected ServiceService $serviceService;

    /**
     * ProductsController constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryService $categoryService
     * @param ServiceService $serviceService
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryService $categoryService,
        ServiceService $serviceService
    ) {
        $this->productRepository = $productRepository;
        $this->categoryService = $categoryService;
        $this->serviceService = $serviceService;
    }

    /**
     * Display a listing of the products.
     *
     * @param ProductFilter $filter
     * @return View
     */
    public function index(ProductFilter $filter): View
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

    /**
     * Display the specified product.
     *
     * @param int $product_id
     * @return View
     */
    public function show(int $product_id): View
    {
        $product = $this->productRepository->findById($product_id);
        $category = $product->categories->first();
        $services = $this->serviceService->getAll();

        return view('product', compact('product', 'category', 'services'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $dto = new CreateProductDTO($request->validated());
        $product = $this->productRepository->create($dto->toArray());
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    /**
     * Update the specified product in storage.
     *
     * @param UpdateProductRequest $request
     * @param int $product_id
     * @return JsonResponse
     */
    public function update(UpdateProductRequest $request, int $product_id): JsonResponse
    {
        $dto = new CreateProductDTO($request->validated());
        $product = $this->productRepository->update($product_id, $dto->toArray());
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param int $product_id
     * @return JsonResponse
     */
    public function destroy(int $product_id): JsonResponse
    {
        $this->productRepository->delete($product_id);

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
