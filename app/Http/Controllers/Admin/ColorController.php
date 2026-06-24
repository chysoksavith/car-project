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
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ColorController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly ColorService $colorService,
    ) {
        $this->authorizeResource(Color::class, 'color');
    }

    // # Display listing of resource
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

    // # Show form for creating resource
    public function create(): Response
    {
        return Inertia::render('Admin/Colors/Create');
    }

    // # Store newly created resource
    public function store(StoreColorRequest $request): RedirectResponse
    {
        $this->colorService->create($request->validated());
        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully.');
    }

    // # Show form for editing resource
    public function edit(Color $color): Response
    {
        return Inertia::render('Admin/Colors/Edit', [
            'color' => new ColorResource($color),
        ]);
    }

    // # Update specified resource
    public function update(UpdateColorRequest $request, Color $color): RedirectResponse
    {
        $this->colorService->update($color, $request->validated());
        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(Color $color): RedirectResponse
    {
        $this->colorService->delete($color);
        return back()->with('success', 'Color deleted successfully.');
    }
}
