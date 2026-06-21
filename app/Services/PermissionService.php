<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Return all permissions ordered alphabetically.
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Permission::orderBy('id', 'desc')->get(['id', 'name']);
    }

    /**
     * Get paginated permissions with optional search query.
     */
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 15)
    {
        return Permission::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Create a new permission.
     */
    public function create(string $name): Permission
    {
        return Permission::create(['name' => $name, 'guard_name' => 'web']);
    }

    /**
     * Rename an existing permission.
     */
    public function update(Permission $permission, string $name): Permission
    {
        $permission->update(['name' => $name]);

        return $permission;
    }

    /**
     * Delete a permission.
     */
    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}
