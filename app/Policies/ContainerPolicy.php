<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Container;

class ContainerPolicy
{
    // # Check if user can view container list
    public function viewAny(User $user): bool
    {
        return $user->can('containers.view');
    }

    // # Check if user can view a specific container
    public function view(User $user, Container $container): bool
    {
        return $user->can('containers.view');
    }

    // # Check if user can create containers
    public function create(User $user): bool
    {
        return $user->can('containers.create');
    }

    // # Check if user can update a container
    public function update(User $user, Container $container): bool
    {
        return $user->can('containers.edit');
    }

    // # Check if user can delete a container
    public function delete(User $user, Container $container): bool
    {
        return $user->can('containers.delete');
    }
}
