<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter
{
    protected Request $request;
    protected Builder $builder;

    /**
     * ProductFilter constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters to the builder.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
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

    /**
     * Filter by category.
     *
     * @return void
     */
    protected function filterByCategory(): void
    {
        $this->builder->whereHas('categories', function($query) {
            $query->where('categories.id', $this->request->category_id);
        });
    }

    /**
     * Filter by minimum price.
     *
     * @return void
     */
    protected function filterByMinPrice(): void
    {
        $this->builder->where('price', '>=', $this->request->min_price);
    }

    /**
     * Filter by maximum price.
     *
     * @return void
     */
    protected function filterByMaxPrice(): void
    {
        $this->builder->where('price', '<=', $this->request->max_price);
    }

    /**
     * Apply sorting.
     *
     * @return void
     */
    protected function applySorting(): void
    {
        $sortField = $this->request->get('sort_by', 'created_at');
        $sortDirection = $this->request->get('sort_direction', 'asc');

        $this->builder->orderBy($sortField, $sortDirection);
    }
}
