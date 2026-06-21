<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * Get paginated roles with optional search.
     */
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

    /**
     * Create a new role and assign permissions.
     */
    public function create(string $name, array $permissions = []): Role
    {
        $role = Role::create(['name' => $name, 'guard_name' => 'web']);
        $role->syncPermissions($permissions);

        return $role;
    }

    /**
     * Update an existing role's name and permissions.
     */
    public function update(Role $role, string $name, array $permissions = []): Role
    {
        $role->update(['name' => $name]);
        $role->syncPermissions($permissions);

        return $role->fresh('permissions');
    }

    /**
     * Delete a role.
     */
    public function delete(Role $role): void
    {
        $role->delete();
    }
}
