<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Container;
use App\Support\ServiceLogger;
use Illuminate\Support\Facades\DB;

class ContainerService
{
    public function __construct(private readonly CarService $carService)
    {
    }

    // # Create Container with optional nested cars
    public function createContainer(array $data): Container
    {
        return DB::transaction(function () use ($data) {
            $cars = $data['cars'] ?? [];
            unset($data['cars']);

            $container = Container::create($data);

            foreach ($cars as $carData) {
                $this->carService->create(
                    $this->applyCarDefaults($carData, $container->id)
                );
            }

            ServiceLogger::created('Container', $container->id, [
                'container_number' => $container->container_number,
                'bl_number'        => $container->bl_number,
                'cars_count'       => count($cars),
            ]);

            return $container;
        });
    }

    // # Update Container and sync nested cars
    public function updateContainer(Container $container, array $data): bool
    {
        return DB::transaction(function () use ($container, $data) {
            $cars = $data['cars'] ?? [];
            unset($data['cars']);

            $updated = $container->update($data);

            $existingCars = $container->cars()->get()->keyBy('id');
            $existingCarIds = $existingCars->keys()->all();
            $submittedCarIds = [];

            foreach ($cars as $carData) {
                $carData['container_id'] = $container->id;

                if (isset($carData['id'])) {
                    $car = $existingCars->get($carData['id']);
                    if ($car) {
                        $this->carService->update($car, $carData);
                        $submittedCarIds[] = $carData['id'];
                    }
                } else {
                    $newCar = $this->carService->create(
                        $this->applyCarDefaults($carData, $container->id)
                    );
                    $submittedCarIds[] = $newCar->id;
                }
            }

            // # Soft-delete cars that were removed from the container
            $carsToDeleteIds = array_diff($existingCarIds, $submittedCarIds);
            if (!empty($carsToDeleteIds)) {
                foreach ($carsToDeleteIds as $carId) {
                    $carToDelete = $existingCars->get($carId);
                    if ($carToDelete) {
                        $this->carService->delete($carToDelete);
                    }
                }
            }

            ServiceLogger::updated('Container', $container->id, [
                'container_number' => $container->container_number,
                'cars_updated'     => count($submittedCarIds),
                'cars_removed'     => count($carsToDeleteIds),
            ]);

            return $updated;
        });
    }

    // # Soft-delete Container and all its cars
    public function deleteContainer(Container $container): bool
    {
        return DB::transaction(function () use ($container) {
            // # Cascade soft-delete to all child cars
            // # Retrieve all cars first to avoid chunking issues with mutating queries
            $container->cars()->get()->each(fn (Car $car) => $this->carService->delete($car));

            $deleted = $container->delete();

            ServiceLogger::deleted('Container', $container->id, [
                'container_number' => $container->container_number,
            ]);

            return $deleted;
        });
    }

    // # Helpers

    // # Merge sensible defaults onto a new car payload before persisting.
    private function applyCarDefaults(array $carData, int $containerId): array
    {
        return array_merge([
            'inventory_status' => \App\Enums\InventoryStatus::IN_TRANSIT->value,
            'sales_status'     => \App\Enums\SalesStatus::AVAILABLE->value,
            'condition'        => \App\Enums\CarCondition::NEW->value,
            'quantity'         => 1,
            'is_active'        => true,
            'is_published'     => false,
        ], $carData, [
            'container_id' => $containerId, // # always authoritative
        ]);
    }
}
