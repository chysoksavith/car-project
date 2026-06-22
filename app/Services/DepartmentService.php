<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartmentService
{
    // # Retrieve Paginated With Search
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Department::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // # Create
    public function create(array $data): Department
    {
        return Department::create($data);
    }

    // # Update
    public function update(Department $department, array $data): bool
    {
        return $department->update($data);
    }

    // # Delete
    public function delete(Department $department): ?bool
    {
        return $department->delete();
    }
}
