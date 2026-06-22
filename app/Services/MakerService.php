<?php

namespace App\Services;

use App\Models\Maker;
use Illuminate\Pagination\LengthAwarePaginator;

class MakerService
{
    // # Retrieve Paginated With Search
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Maker::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // # Create
    public function create(array $data): Maker
    {
        return Maker::create($data);
    }

    // # Update
    public function update(Maker $maker, array $data): bool
    {
        return $maker->update($data);
    }

    // # Delete
    public function delete(Maker $maker): ?bool
    {
        return $maker->delete();
    }
}
