<?php

namespace App\Services;

use App\Models\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContainerService
{
    public function __construct(private readonly CarService $carService)
    {
    }

    // # Create Container
    public function createContainer(array $data): Container
    {
        DB::beginTransaction();
        try {
            $cars = $data['cars'] ?? [];
            unset($data['cars']);

            // company_id will be automatically filled by HasTenant trait or user's session
            $container = Container::create($data);

            if (!empty($cars)) {
                foreach ($cars as $carData) {
                    $carData['container_id'] = $container->id;
                    $carData['inventory_status'] = $carData['inventory_status'] ?? 'IN_TRANSIT';
                    $carData['sales_status'] = $carData['sales_status'] ?? 'AVAILABLE';
                    $carData['condition'] = $carData['condition'] ?? 'NEW';
                    $carData['quantity'] = $carData['quantity'] ?? 1;
                    $carData['is_active'] = $carData['is_active'] ?? true;
                    $carData['is_published'] = $carData['is_published'] ?? false;
                    $this->carService->create($carData);
                }
            }

            DB::commit();

            return $container;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create container: ' . $e->getMessage());
            throw $e;
        }
    }

    // # Update Container
    public function updateContainer(Container $container, array $data): bool
    {
        DB::beginTransaction();
        try {
            $cars = $data['cars'] ?? [];
            unset($data['cars']);

            $updated = $container->update($data);

            $existingCarIds = $container->cars()->pluck('id')->toArray();
            $submittedCarIds = [];

            if (!empty($cars)) {
                foreach ($cars as $carData) {
                    $carData['container_id'] = $container->id;
                    if (isset($carData['id'])) {
                        $submittedCarIds[] = $carData['id'];
                        $car = \App\Models\Car::find($carData['id']);
                        if ($car) {
                            $this->carService->update($car, $carData);
                        }
                    } else {
                        $carData['inventory_status'] = $carData['inventory_status'] ?? 'IN_TRANSIT';
                        $carData['sales_status'] = $carData['sales_status'] ?? 'AVAILABLE';
                        $carData['condition'] = $carData['condition'] ?? 'NEW';
                        $carData['quantity'] = $carData['quantity'] ?? 1;
                        $carData['is_active'] = $carData['is_active'] ?? true;
                        $carData['is_published'] = $carData['is_published'] ?? false;
                        $newCar = $this->carService->create($carData);
                        $submittedCarIds[] = $newCar->id;
                    }
                }
            }

            $carsToDelete = array_diff($existingCarIds, $submittedCarIds);
            if (!empty($carsToDelete)) {
                foreach ($carsToDelete as $carId) {
                    $car = \App\Models\Car::find($carId);
                    if ($car) {
                        $this->carService->delete($car);
                    }
                }
            }

            DB::commit();

            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update container: ' . $e->getMessage());
            throw $e;
        }
    }

    // # Delete Container
    public function deleteContainer(Container $container): bool
    {
        DB::beginTransaction();
        try {
            $deleted = $container->delete();
            DB::commit();

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete container: ' . $e->getMessage());
            throw $e;
        }
    }
}
