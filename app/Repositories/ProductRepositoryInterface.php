<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function getAllWithFilter($filter);
    public function findById($product_id);
    public function create(array $data);
    public function update($product_id, array $data);
    public function delete($product_id);
}
