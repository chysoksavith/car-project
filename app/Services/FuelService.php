<?php

namespace App\Services;

use App\Models\Fuel;
use Illuminate\Pagination\LengthAwarePaginator;

class FuelService
{
    // # Retrieve Paginated With Search
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Fuel::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // # Create
    public function create(array $data): Fuel
    {
        return Fuel::create($data);
    }

    // # Update
    public function update(Fuel $fuel, array $data): bool
    {
        return $fuel->update($data);
    }

    // # Delete
    public function delete(Fuel $fuel): ?bool
    {
        return $fuel->delete();
    }
}
