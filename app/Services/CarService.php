<?php

namespace App\Services;

use App\Models\Car;
use App\Support\ServiceLogger;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarService
{
    // # Retrieve Paginated With Search
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Car::query()
            ->with(['maker', 'carModel', 'fuel', 'container', 'attachments', 'color'])
            ->where('company_id', auth()->user()->company_id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('plate_number', 'like', "%{$search}%")
                      ->orWhere('engine_number', 'like', "%{$search}%")
                      ->orWhere('body_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // # Create
    // NOTE: No transaction here — callers (ContainerService, controller) wrap this.
    public function create(array $data): Car
    {
        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();

        $images = $data['images'] ?? [];
        unset($data['images']);

        $car = Car::create($data);

        foreach ($images as $image) {
            $car->attachFile($image, 'car_images');
        }

        ServiceLogger::created('Car', $car->id, [
            'name'         => $car->name,
            'container_id' => $car->container_id,
        ]);

        return $car;
    }

    // # Update
    public function update(Car $car, array $data): bool
    {
        $images = $data['images'] ?? [];
        unset($data['images']);

        $updated = $car->update($data);

        if (!empty($images)) {
            // Replace all attachments with the new set
            foreach ($car->attachments as $attachment) {
                Storage::disk($attachment->disk)->delete($attachment->file_path);
                $attachment->delete();
            }

            foreach ($images as $image) {
                $car->attachFile($image, 'car_images');
            }
        }

        ServiceLogger::updated('Car', $car->id, [
            'name'         => $car->name,
            'container_id' => $car->container_id,
        ]);

        return $updated;
    }

    // # Soft-delete
    public function delete(Car $car): ?bool
    {
        $deleted = $car->delete();

        ServiceLogger::deleted('Car', $car->id, [
            'name'         => $car->name,
            'container_id' => $car->container_id,
        ]);

        return $deleted;
    }
}
