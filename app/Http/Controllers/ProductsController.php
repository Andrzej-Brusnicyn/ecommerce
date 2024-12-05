<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Repositories\CategoryRepositoryInterface;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\ServiceRepositoryInterface;
use App\Services\PriceConversionService;
use App\DTO\CreateProductDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Jobs\ExportProductsJob;
use Illuminate\Contracts\Filesystem\Filesystem;

class ProductsController extends Controller
{
    protected ProductRepositoryInterface $productRepository;
    protected ServiceRepositoryInterface $serviceRepository;
    protected CategoryRepositoryInterface $categoryRepository;
    protected PriceConversionService $priceConversionService;

    /**
     * ProductsController constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ServiceRepositoryInterface $serviceRepository
     * @param PriceConversionService $priceConversionService
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ServiceRepositoryInterface $serviceRepository,
        CategoryRepositoryInterface $categoryRepository,
        PriceConversionService $priceConversionService
    ) {
        $this->productRepository = $productRepository;
        $this->serviceRepository = $serviceRepository;
        $this->categoryRepository = $categoryRepository;
        $this->priceConversionService = $priceConversionService;
    }

    /**
     * Show all products.
     *
     * @return View
     */
    public function getAll(): View
    {
        $products = $this->productRepository->getAll();
        $categories = $this->categoryRepository->getAll();

        return view('admin', compact('products', 'categories'));
    }

    /**
     * Export all products to an S3 bucket.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param Filesystem $filesystem
     * @return JsonResponse
     */
    public function exportProductsToS3(ProductRepositoryInterface $productRepository, Filesystem $filesystem): JsonResponse
    {

        ExportProductsJob::dispatch($productRepository, $filesystem)->onQueue('default');

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
        $categories = $this->categoryRepository->getAll();

        $products = $this->priceConversionService->convertPrices($products, config('constants.currencies'));

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
        $services = $this->serviceRepository->getAll();

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
