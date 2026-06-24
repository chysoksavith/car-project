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

        foreach ($images as $index => $image) {
            $car->attachFile($image, 'car_images', 'public', $index);
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
        $deletedImages = $data['deleted_images'] ?? [];
        $existingImageOrder = $data['existing_image_order'] ?? [];
        unset($data['images'], $data['deleted_images'], $data['existing_image_order']);

        $updated = $car->update($data);

        if (!empty($deletedImages)) {
            foreach ($car->attachments as $attachment) {
                if (in_array($attachment->id, $deletedImages)) {
                    Storage::disk($attachment->disk)->delete($attachment->file_path);
                    $attachment->delete();
                }
            }
        }

        $nextSortOrder = 0;
        if (!empty($existingImageOrder)) {
            foreach ($existingImageOrder as $index => $attachmentId) {
                // Update the sort order of existing attachments
                $car->attachments()->where('id', $attachmentId)->update(['sort_order' => $index]);
            }
            $nextSortOrder = count($existingImageOrder);
        } else {
            // Re-index remaining attachments to ensure consistency
            $remainingAttachments = $car->attachments()->get();
            foreach ($remainingAttachments as $index => $attachment) {
                $attachment->update(['sort_order' => $index]);
            }
            $nextSortOrder = $remainingAttachments->count();
        }

        if (!empty($images)) {
            foreach ($images as $image) {
                $car->attachFile($image, 'car_images', 'public', $nextSortOrder);
                $nextSortOrder++;
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
