<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function getAll();
    public function getAllWithFilter($filter);
    public function findById(int $product_id);
    public function create(array $data);
    public function update(int $product_id, array $data);
    public function delete(int $product_id);
}
