<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    // # Retrieve Paginated With Search
    public function getPaginatedWithSearch(?string $search, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Role::withCount('permissions')
            ->with('permissions:id,name')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    // # Create
    public function create(string $name, array $permissions = []): Role
    {
        $role = Role::create(['name' => $name, 'guard_name' => 'web']);
        $role->syncPermissions($permissions);

        return $role;
    }

    // # Update
    public function update(Role $role, string $name, array $permissions = []): Role
    {
        $role->update(['name' => $name]);
        $role->syncPermissions($permissions);

        return $role->fresh('permissions');
    }

    // # Delete
    public function delete(Role $role): void
    {
        $role->delete();
    }
}
