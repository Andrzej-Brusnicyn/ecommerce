<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Service;

class ServiceRepository implements ServiceRepositoryInterface
{
    /**
     * Get all services.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Service::all();
    }
}
