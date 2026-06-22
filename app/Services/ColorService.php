<?php

namespace App\Services;

use App\Models\Color;
use Illuminate\Pagination\LengthAwarePaginator;

class ColorService
{
    // # Retrieve Paginated With Search
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Color::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('hex_code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // # Create
    public function create(array $data): Color
    {
        return Color::create($data);
    }

    // # Update
    public function update(Color $color, array $data): Color
    {
        $color->update($data);
        return $color;
    }

    // # Delete
    public function delete(Color $color): bool
    {
        return $color->delete();
    }
}
