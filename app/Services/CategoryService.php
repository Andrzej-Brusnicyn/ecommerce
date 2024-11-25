<?php

namespace App\Services;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Category::all();
    }
}
