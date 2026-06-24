<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    // # Check if user can view roles list
    public function viewAny(User $user): bool
    {
        return $user->can('roles.view');
    }

    // # Check if user can view a specific role
    public function view(User $user, Role $role): bool
    {
        return $user->can('roles.view');
    }

    // # Check if user can create roles
    public function create(User $user): bool
    {
        return $user->can('roles.create');
    }

    // # Check if user can update a role
    public function update(User $user, Role $role): bool
    {
        return $user->can('roles.edit');
    }

    // # Check if user can delete a role
    public function delete(User $user, Role $role): bool
    {
        return $user->can('roles.delete');
    }
}
