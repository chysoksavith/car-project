<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // # Check if user can view users list
    public function viewAny(User $user): bool
    {
        return $user->can('users.view');
    }

    // # Check if user can view a specific user
    public function view(User $user, User $model): bool
    {
        return $user->can('users.view');
    }

    // # Check if user can create users
    public function create(User $user): bool
    {
        return $user->can('users.create');
    }

    // # Check if user can update a user
    public function update(User $user, User $model): bool
    {
        return $user->can('users.edit');
    }

    // # Check if user can delete a user
    public function delete(User $user, User $model): bool
    {
        return $user->can('users.delete');
    }
}
