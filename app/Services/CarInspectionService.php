<?php

namespace App\Services;

use App\Models\Car;
use App\Models\CarInspection;
use App\Models\InspectionItem;
use Illuminate\Database\Eloquent\Collection;

class CarInspectionService
{
    // # Get all inspection items for a car
    public function getCarInspections(int $carId): Collection
    {
        return CarInspection::with(['inspectionItem.parent'])
            ->where('car_id', $carId)
            ->get();
    }

    // # Get grouped inspections by main category only
    public function getGroupedCarInspections(int $carId): array
    {
        $inspections = $this->getCarInspections($carId);
        $grouped = [];

        foreach ($inspections as $inspection) {
            $mainCategory = $inspection->inspectionItem->parent?->name_kh
                ?? $inspection->inspectionItem->name_kh;

            if (!isset($grouped[$mainCategory])) {
                $grouped[$mainCategory] = [];
            }

            $grouped[$mainCategory][] = $inspection;
        }

        return $grouped;
    }

    // # Get all available inspection items (main categories and sub-items)
    public function getAllInspectionItems(): Collection
    {
        return InspectionItem::with('parent')
            ->orderBy('parent_id')
            ->orderBy('name_kh')
            ->get();
    }

    // # Initialize inspections for a car (create records for all sub-items)
    public function initializeCarInspections(int $carId): Collection
    {
        $car = Car::find($carId);
        if (!$car) {
            return collect();
        }

        $subItems = InspectionItem::whereNotNull('parent_id')->get();

        foreach ($subItems as $item) {
            CarInspection::firstOrCreate(
                [
                    'car_id' => $carId,
                    'inspection_item_id' => $item->id,
                ],
                [
                    'company_id' => $car->company_id,
                    'cost' => $item->default_price ?? 0,
                    'condition' => null,
                    'note' => null,
                ]
            );
        }

        return $this->getCarInspections($carId);
    }

    // # Update inspection cost
    public function updateInspectionCost(CarInspection $inspection, float $cost): bool
    {
        return $inspection->update(['cost' => $cost]);
    }

    // # Update inspection condition and note
    public function updateInspectionDetails(CarInspection $inspection, array $data): bool
    {
        return $inspection->update($data);
    }

    // # Get cars available for inspection
    public function getCarsForInspection(): Collection
    {
        return Car::with(['maker', 'carModel', 'attachments'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
