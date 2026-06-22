<?php

namespace App\Services;

use App\Models\CarModel;
use Illuminate\Pagination\LengthAwarePaginator;

class CarModelService
{
    // # Retrieve Paginated With Search
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return CarModel::query()
            ->with('maker')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhereHas('maker', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // # Create
    public function create(array $data): CarModel
    {
        return CarModel::create($data);
    }

    // # Update
    public function update(CarModel $carModel, array $data): bool
    {
        return $carModel->update($data);
    }

    // # Delete
    public function delete(CarModel $carModel): ?bool
    {
        return $carModel->delete();
    }
}
