<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Fuel;

class FuelPolicy
{
    // # Check if user can view fuel list
    public function viewAny(User $user): bool
    {
        return $user->can('fuels.view');
    }

    // # Check if user can view a specific fuel
    public function view(User $user, Fuel $fuel): bool
    {
        return $user->can('fuels.view');
    }

    // # Check if user can create fuels
    public function create(User $user): bool
    {
        return $user->can('fuels.create');
    }

    // # Check if user can update a fuel
    public function update(User $user, Fuel $fuel): bool
    {
        return $user->can('fuels.edit');
    }

    // # Check if user can delete a fuel
    public function delete(User $user, Fuel $fuel): bool
    {
        return $user->can('fuels.delete');
    }
}
