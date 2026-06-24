<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InspectionItem;

class InspectionItemPolicy
{
    // # Check if user can view inspection item list
    public function viewAny(User $user): bool
    {
        return $user->can('inspection_items.view');
    }

    // # Check if user can view a specific inspection item
    public function view(User $user, InspectionItem $inspectionItem): bool
    {
        return $user->can('inspection_items.view');
    }

    // # Check if user can create inspection items
    public function create(User $user): bool
    {
        return $user->can('inspection_items.create');
    }

    // # Check if user can update an inspection item
    public function update(User $user, InspectionItem $inspectionItem): bool
    {
        return $user->can('inspection_items.edit');
    }

    // # Check if user can delete an inspection item
    public function delete(User $user, InspectionItem $inspectionItem): bool
    {
        return $user->can('inspection_items.delete');
    }
}
