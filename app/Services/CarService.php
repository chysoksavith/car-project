<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    public function create(array $data): Car
    {
        DB::beginTransaction();
        try {
            $data['slug'] = Str::slug($data['name']) . '-' . uniqid();

            $images = $data['images'] ?? [];
            unset($data['images']);

            $car = Car::create($data);

            if (!empty($images)) {
                foreach ($images as $image) {
                    $car->attachFile($image, 'car_images');
                }
            }

            DB::commit();

            return $car;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create car: ' . $e->getMessage());
            throw $e;
        }
    }

    // # Update
    public function update(Car $car, array $data): bool
    {
        DB::beginTransaction();
        try {
            $images = $data['images'] ?? [];
            unset($data['images']);

            $updated = $car->update($data);

            if (!empty($images)) {
                // Delete old attachments and physical files before adding new ones
                foreach ($car->attachments as $attachment) {
                    \Illuminate\Support\Facades\Storage::disk($attachment->disk)->delete($attachment->file_path);
                    $attachment->delete();
                }

                foreach ($images as $image) {
                    $car->attachFile($image, 'car_images');
                }
            }

            DB::commit();

            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update car: ' . $e->getMessage());
            throw $e;
        }
    }

    // # Delete
    public function delete(Car $car): ?bool
    {
        DB::beginTransaction();
        try {
            $deleted = $car->delete();
            DB::commit();

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete car: ' . $e->getMessage());
            throw $e;
        }
    }
}
