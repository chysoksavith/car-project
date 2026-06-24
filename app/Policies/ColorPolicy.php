<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Color;

class ColorPolicy
{
    // # Check if user can view color list
    public function viewAny(User $user): bool
    {
        return $user->can('colors.view');
    }

    // # Check if user can view a specific color
    public function view(User $user, Color $color): bool
    {
        return $user->can('colors.view');
    }

    // # Check if user can create colors
    public function create(User $user): bool
    {
        return $user->can('colors.create');
    }

    // # Check if user can update a color
    public function update(User $user, Color $color): bool
    {
        return $user->can('colors.edit');
    }

    // # Check if user can delete a color
    public function delete(User $user, Color $color): bool
    {
        return $user->can('colors.delete');
    }
}
