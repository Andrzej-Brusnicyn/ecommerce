<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllWithFilter($filter)
    {
        return Product::filter($filter)->get();
    }

    public function findById($product_id)
    {
        return Product::with('categories')->find($product_id);
    }

    public function create(array $data)
    {
        $product = Product::create($data);
        if (isset($data['category_id'])) {
            $product->categories()->attach($data['category_id']);
        }
        return $product;
    }

    public function update($product_id, array $data)
    {
        $product = Product::find($product_id);
        $product->update($data);
        if (isset($data['category_id'])) {
            $product->categories()->sync([$data['category_id']]);
        }
        return $product;
    }

    public function delete($product_id)
    {
        $product = Product::find($product_id);
        $product->categories()->detach();
        return $product->delete();
    }
}
