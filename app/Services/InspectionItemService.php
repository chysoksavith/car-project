<?php

namespace App\Services;

use App\Models\InspectionItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class InspectionItemService
{
    /**
     * Get paginated inspection items with search, eager loading their parent.
     */
    public function getPaginatedWithSearch(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return InspectionItem::with('parent')
            ->when($search, function ($query, $search) {
                $query->where('name_kh', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%")
                      ->orWhereHas('parent', function ($q) use ($search) {
                          $q->where('name_kh', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all main categories (items with no parent) for the dropdown selection.
     */
    public function getMainCategories(): Collection
    {
        return InspectionItem::whereNull('parent_id')
            ->orderBy('name_kh')
            ->get();
    }

    /**
     * Create a new inspection item.
     */
    public function createItem(array $data): InspectionItem
    {
        return InspectionItem::create($data);
    }

    /**
     * Update an inspection item.
     */
    public function updateItem(InspectionItem $item, array $data): bool
    {
        return $item->update($data);
    }

    /**
     * Delete an inspection item.
     */
    public function deleteItem(InspectionItem $item): ?bool
    {
        if ($item->children()->exists()) {
            throw new \Exception('Cannot delete this category because it contains sub-items. Please delete the items first.');
        }

        return $item->delete();
    }
}
