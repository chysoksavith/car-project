<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;

class CompanyPolicy
{
    // # Check if user can view company list
    public function viewAny(User $user): bool
    {
        return $user->can('companies.view');
    }

    // # Check if user can view a specific company
    public function view(User $user, Company $company): bool
    {
        return $user->can('companies.view');
    }

    // # Check if user can create companies
    public function create(User $user): bool
    {
        return $user->can('companies.create');
    }

    // # Check if user can update a company
    public function update(User $user, Company $company): bool
    {
        return $user->can('companies.edit');
    }

    // # Check if user can delete a company
    public function delete(User $user, Company $company): bool
    {
        return $user->can('companies.delete');
    }
}
