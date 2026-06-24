<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Department;

class DepartmentPolicy
{
    // # Check if user can view department list
    public function viewAny(User $user): bool
    {
        return $user->can('departments.view');
    }

    // # Check if user can view a specific department
    public function view(User $user, Department $department): bool
    {
        return $user->can('departments.view');
    }

    // # Check if user can create departments
    public function create(User $user): bool
    {
        return $user->can('departments.create');
    }

    // # Check if user can update a department
    public function update(User $user, Department $department): bool
    {
        return $user->can('departments.edit');
    }

    // # Check if user can delete a department
    public function delete(User $user, Department $department): bool
    {
        return $user->can('departments.delete');
    }
}
