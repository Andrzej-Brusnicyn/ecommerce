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
use Illuminate\Http\RedirectResponse;
use App\Jobs\ExportProductsJob;

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
     * Show all products.
     *
     * @return View
     */
    public function getAll(): View
    {
        $products = $this->productRepository->getAll();
        $categories = $this->categoryService->getAll();

        return view('admin', compact('products', 'categories'));
    }

    /**
     * Export all products to an S3 bucket.
     *
     * @return JsonResponse
     */
    public function exportProductsToS3(): JsonResponse
    {
        ExportProductsJob::dispatch()->onQueue('default');

        return response()->json(['message' => 'Export task added to the queue'], 200);
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

        $currencies = config('constants.currencies');

        foreach ($products as $product) {
            foreach ($currencies as $currency) {
                $priceKey = 'price_' . strtolower($currency);
                $product->$priceKey = $currencyService->convert($product->price, $currency);
            }
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
     * @return RedirectResponse
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $dto = new CreateProductDTO($request->validated());
        $this->productRepository->create($dto->toArray());
        return redirect()->route('admin')->with('success', 'Product successfully added');
    }

    /**
     * Update the specified product in storage.
     *
     * @param UpdateProductRequest $request
     * @param int $product_id
     * @return RedirectResponse
     */
    public function update(UpdateProductRequest $request, int $product_id): RedirectResponse
    {
        $dto = new CreateProductDTO($request->validated());
        $this->productRepository->update($product_id, $dto->toArray());
        return redirect()->route('admin')->with('success', 'Product successfully updated');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param int $product_id
     * @return RedirectResponse
     */
    public function destroy(int $product_id): RedirectResponse
    {
        $this->productRepository->delete($product_id);

        return redirect()->route('admin')->with('success', 'Product successfully deleted');
    }
}
