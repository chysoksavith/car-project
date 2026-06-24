<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Supplier;

class SupplierPolicy
{
    // # Check if user can view supplier list
    public function viewAny(User $user): bool
    {
        return $user->can('suppliers.view');
    }

    // # Check if user can view a specific supplier
    public function view(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.view');
    }

    // # Check if user can create suppliers
    public function create(User $user): bool
    {
        return $user->can('suppliers.create');
    }

    // # Check if user can update a supplier
    public function update(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.edit');
    }

    // # Check if user can delete a supplier
    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.delete');
    }
}
