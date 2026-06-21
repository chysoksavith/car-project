<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Color\StoreColorRequest;
use App\Http\Requests\Admin\Color\UpdateColorRequest;
use App\Http\Resources\Admin\ColorResource;
use App\Models\Color;
use App\Services\ColorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ColorController extends Controller
{
    public function __construct(
        private readonly ColorService $colorService,
    ) {}

    public function index(Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/Colors/Index', [
            'colors'  => ColorResource::collection(
                $this->colorService->getPaginatedWithSearch($search)
            ),
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Colors/Create');
    }

    public function store(StoreColorRequest $request): RedirectResponse
    {
        $this->colorService->create($request->validated());
        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully.');
    }

    public function edit(Color $color): Response
    {
        return Inertia::render('Admin/Colors/Edit', [
            'color' => new ColorResource($color),
        ]);
    }

    public function update(UpdateColorRequest $request, Color $color): RedirectResponse
    {
        $this->colorService->update($color, $request->validated());
        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully.');
    }

    public function destroy(Color $color): RedirectResponse
    {
        $this->colorService->delete($color);
        return back()->with('success', 'Color deleted successfully.');
    }
}
