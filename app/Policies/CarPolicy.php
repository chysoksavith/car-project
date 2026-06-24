<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Car;

class CarPolicy
{
    // # Check if user can view car list
    public function viewAny(User $user): bool
    {
        return $user->can('cars.view');
    }

    // # Check if user can view a specific car
    public function view(User $user, Car $car): bool
    {
        return $user->can('cars.view');
    }

    // # Check if user can create cars
    public function create(User $user): bool
    {
        return $user->can('cars.create');
    }

    // # Check if user can update a car
    public function update(User $user, Car $car): bool
    {
        return $user->can('cars.edit');
    }

    // # Check if user can delete a car
    public function delete(User $user, Car $car): bool
    {
        return $user->can('cars.delete');
    }
}
