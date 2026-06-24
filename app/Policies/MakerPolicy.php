<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Maker;

class MakerPolicy
{
    // # Check if user can view maker list
    public function viewAny(User $user): bool
    {
        return $user->can('makers.view');
    }

    // # Check if user can view a specific maker
    public function view(User $user, Maker $maker): bool
    {
        return $user->can('makers.view');
    }

    // # Check if user can create makers
    public function create(User $user): bool
    {
        return $user->can('makers.create');
    }

    // # Check if user can update a maker
    public function update(User $user, Maker $maker): bool
    {
        return $user->can('makers.edit');
    }

    // # Check if user can delete a maker
    public function delete(User $user, Maker $maker): bool
    {
        return $user->can('makers.delete');
    }
}
