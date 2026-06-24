<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CarModel;

class CarModelPolicy
{
    // # Check if user can view car model list
    public function viewAny(User $user): bool
    {
        return $user->can('car_models.view');
    }

    // # Check if user can view a specific car model
    public function view(User $user, CarModel $carModel): bool
    {
        return $user->can('car_models.view');
    }

    // # Check if user can create car models
    public function create(User $user): bool
    {
        return $user->can('car_models.create');
    }

    // # Check if user can update a car model
    public function update(User $user, CarModel $carModel): bool
    {
        return $user->can('car_models.edit');
    }

    // # Check if user can delete a car model
    public function delete(User $user, CarModel $carModel): bool
    {
        return $user->can('car_models.delete');
    }
}
