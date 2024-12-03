<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all services.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Category::all();
    }
}
