<?php

namespace App\Services;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

class ServiceService
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
