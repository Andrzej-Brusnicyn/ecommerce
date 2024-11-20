<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;

trait HasFilter
{
    /**
     * Apply the filter to the builder.
     *
     * @param Builder $builder
     * @param mixed $filter
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $filter): Builder
    {
        return $filter->apply($builder);
    }
}
