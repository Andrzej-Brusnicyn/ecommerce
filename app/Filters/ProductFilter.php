<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        if ($this->request->filled('category_id')) {
            $this->filterByCategory();
        }

        if ($this->request->filled('min_price')) {
            $this->filterByMinPrice();
        }

        if ($this->request->filled('max_price')) {
            $this->filterByMaxPrice();
        }

        $this->applySorting();

        return $this->builder;
    }

    protected function filterByCategory()
    {
        $this->builder->whereHas('categories', function($query) {
            $query->where('categories.id', $this->request->category_id);
        });
    }

    protected function filterByMinPrice()
    {
        $this->builder->where('price', '>=', $this->request->min_price);
    }

    protected function filterByMaxPrice()
    {
        $this->builder->where('price', '<=', $this->request->max_price);
    }

    protected function applySorting()
    {
        $sortField = $this->request->get('sort_by', 'created_at');
        $sortDirection = $this->request->get('sort_direction', 'asc');

        $this->builder->orderBy($sortField, $sortDirection);
    }
}
