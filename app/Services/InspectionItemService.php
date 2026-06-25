<?php

namespace App\Services;

use App\Models\InspectionItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class InspectionItemService
{
    // # Retrieve Paginated With Search
    public function getPaginatedWithSearch(?string $search, ?string $categoryFilter = null, ?string $statusFilter = null, int $perPage = 10): LengthAwarePaginator
    {
        return InspectionItem::with('parent')
            ->when($search, function ($query, $search) {
                $query->where('name_kh', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%")
                      ->orWhereHas('parent', function ($q) use ($search) {
                          $q->where('name_kh', 'like', "%{$search}%");
                      });
            })
            ->when($categoryFilter, function ($query, $categoryFilter) {
                $query->where('parent_id', $categoryFilter);
            })
            ->when($statusFilter !== null, function ($query) use ($statusFilter) {
                $query->where('is_active', $statusFilter === '1');
            })
            ->latest()
            ->paginate($perPage);
    }

    // # Retrieve Main Categories
    public function getMainCategories(): Collection
    {
        return InspectionItem::whereNull('parent_id')
            ->orderBy('name_kh')
            ->get();
    }

    // # Create Item
    public function createItem(array $data): InspectionItem
    {
        return InspectionItem::create($data);
    }

    // # Update Item
    public function updateItem(InspectionItem $item, array $data): bool
    {
        return $item->update($data);
    }

    // # Delete Item
    public function deleteItem(InspectionItem $item): ?bool
    {
        if ($item->children()->exists()) {
            throw new \Exception('Cannot delete this category because it contains sub-items. Please delete the items first.');
        }

        return $item->delete();
    }
}
