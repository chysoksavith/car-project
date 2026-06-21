<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    /**
     * Return a lean list of locations, with HTTP + server-side caching.
     * Queries are cheap after migration indexes are applied.
     *
     * Query params:
     *   type        = province | district | commune | village
     *   parent_code = parent location code (optional)
     */
    public function index(Request $request): JsonResponse
    {
        $type       = $request->query('type', 'province');
        $parentCode = $request->query('parent_code');

        // Build a deterministic cache key per unique query
        $cacheKey = 'locations:' . $type . ':' . ($parentCode ?? 'root');

        $data = Cache::remember($cacheKey, now()->addDay(), function () use ($type, $parentCode) {
            return Location::query()
                ->where('type', $type)
                ->when($parentCode, fn ($q) => $q->where('parent_code', $parentCode))
                // Only the 3 columns the frontend needs — trims response size ~50%
                ->select(['code', 'name_en', 'name_km'])
                ->orderBy('name_en')
                ->get();
        });

        return response()
            ->json($data)
            // Browser HTTP caching: re-use result for 1 hour without another round-trip
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
