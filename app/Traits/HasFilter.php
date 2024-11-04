<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;

trait HasFilter
{
    public function scopeFilter(Builder $builder, $filter): Builder
    {
        return $filter->apply($builder);
    }
}
