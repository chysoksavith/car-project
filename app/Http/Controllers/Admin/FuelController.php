<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Fuel\StoreFuelRequest;
use App\Http\Requests\Admin\Fuel\UpdateFuelRequest;
use App\Http\Resources\Admin\FuelResource;
use App\Models\Fuel;
use App\Services\FuelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FuelController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly FuelService $fuelService,
    ) {
        $this->authorizeResource(Fuel::class, 'fuel');
    }

    // # Display listing of resource
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/Fuels/Index', [
            'fuels'  => FuelResource::collection(
                $this->fuelService->getPaginatedWithSearch($search)
            ),
            'filters' => ['search' => $search],
        ]);
    }

    // # Show form for creating resource
    public function create(): Response
    {
        return Inertia::render('Admin/Fuels/Create');
    }

    // # Store newly created resource
    public function store(StoreFuelRequest $request): RedirectResponse
    {
        $this->fuelService->create($request->validated());
        return redirect()->route('admin.fuels.index')->with('success', 'Fuel created successfully.');
    }

    // # Show form for editing resource
    public function edit(Fuel $fuel): Response
    {
        return Inertia::render('Admin/Fuels/Edit', [
            'fuel' => new FuelResource($fuel),
        ]);
    }

    // # Update specified resource
    public function update(UpdateFuelRequest $request, Fuel $fuel): RedirectResponse
    {
        $this->fuelService->update($fuel, $request->validated());
        return redirect()->route('admin.fuels.index')->with('success', 'Fuel updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(Fuel $fuel): RedirectResponse
    {
        $this->fuelService->delete($fuel);
        return back()->with('success', 'Fuel deleted successfully.');
    }
}
