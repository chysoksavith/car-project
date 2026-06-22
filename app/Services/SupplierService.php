<?php

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierService
{
    // # Retrieve Paginated With Search
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Supplier::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('contact_person', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);
    }

    // # Retrieve All
    public function getAll(): Collection
    {
        return Supplier::all();
    }

    // # Create Supplier
    public function createSupplier(array $data): Supplier
    {
        return Supplier::create($data);
    }

    // # Update Supplier
    public function updateSupplier(Supplier $supplier, array $data): bool
    {
        return $supplier->update($data);
    }

    // # Delete Supplier
    public function deleteSupplier(Supplier $supplier): bool|null
    {
        return $supplier->delete();
    }
}
