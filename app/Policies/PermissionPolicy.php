<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    // # Check if user can view permissions list
    public function viewAny(User $user): bool
    {
        return $user->can('permissions.view');
    }

    // # Check if user can view a specific permission
    public function view(User $user, Permission $permission): bool
    {
        return $user->can('permissions.view');
    }

    // # Check if user can create permissions
    public function create(User $user): bool
    {
        return $user->can('permissions.create');
    }

    // # Check if user can update a permission
    public function update(User $user, Permission $permission): bool
    {
        return $user->can('permissions.edit');
    }

    // # Check if user can delete a permission
    public function delete(User $user, Permission $permission): bool
    {
        return $user->can('permissions.delete');
    }
}
