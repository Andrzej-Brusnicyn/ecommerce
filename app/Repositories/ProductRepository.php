<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;

class ProductRepository implements ProductRepositoryInterface
{

    /**
     * Get all products without filters or pagination.
     *
     * @return LazyCollection
     */
    public function getAll(): LazyCollection
    {
        return Product::cursor();
    }

    /**
     * Get all products with filter.
     *
     * @param mixed $filter
     * @return LengthAwarePaginator
     */
    public function getAllWithFilter($filter): LengthAwarePaginator
    {
        return Product::filter($filter)->paginate(config('constants.pagination'));
    }

    /**
     * Find a product by ID.
     *
     * @param int $product_id
     * @return Product|null
     */
    public function findById(int $product_id): ?Product
    {
        return Product::with('categories')->find($product_id);
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        $product = Product::create($data);
        if (isset($data['category_id'])) {
            $product->categories()->attach($data['category_id']);
        }

        return $product;
    }

    /**
     * Update an existing product.
     *
     * @param int $product_id
     * @param array $data
     * @return Product
     */
    public function update(int $product_id, array $data): Product
    {
        $product = Product::find($product_id);
        $product->update($data);
        if (isset($data['category_id'])) {
            $product->categories()->sync([$data['category_id']]);
        }

        return $product;
    }

    /**
     * Delete a product by ID.
     *
     * @param int $product_id
     * @return bool
     */
    public function delete(int $product_id): bool
    {
        $product = Product::find($product_id);
        $product->categories()->detach();

        return $product->delete();
    }

    /**
     * Process products in chunks.
     *
     * @param int $size
     * @param callable $callback
     * @return void
     */
    public function chunk(int $size, callable $callback): void
    {
        Product::query()->chunk($size, $callback);
    }
}
