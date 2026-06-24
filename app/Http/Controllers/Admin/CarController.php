<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Car\StoreCarRequest;
use App\Http\Requests\Admin\Car\UpdateCarRequest;
use App\Http\Resources\Admin\CarResource;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Container;
use App\Models\Fuel;
use App\Models\Maker;
use App\Services\CarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class CarController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly CarService $carService,
    ) {
        $this->authorizeResource(Car::class, 'car');
    }

    // # Display listing of resource
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/Cars/Index', [
            'cars' => CarResource::collection(
                $this->carService->getPaginatedWithSearch($search)
            ),
            'filters' => ['search' => $search],
        ]);
    }

    // # Show form for creating resource
    public function create(): Response
    {
        return Inertia::render('Admin/Cars/Create', [
            'makers' => Maker::where('is_active', true)->get(['id', 'name']),
            'carModels' => CarModel::where('is_active', true)->get(['id', 'name', 'maker_id']),
            'fuels' => Fuel::where('is_active', true)->get(['id', 'name']),
            'colors' => Color::where('is_active', true)->get(['id', 'name']),
            'containers' => Container::get(['id', 'container_number']),
        ]);
    }

    // # Store newly created resource
    public function store(StoreCarRequest $request): RedirectResponse
    {
        $this->carService->create($request->validated());

        return redirect()->route('admin.cars.index')->with('success', 'Car created successfully.');
    }

    // # Display the specified resource
    public function show(Car $car): Response
    {
        $car->load(['maker', 'carModel', 'color', 'fuel', 'container', 'attachments']);

        return Inertia::render('Admin/Cars/Show', [
            'car' => (new CarResource($car))->resolve(),
        ]);
    }

    // # Show form for editing resource
    public function edit(Car $car): Response
    {
        $car->load('attachments');

        return Inertia::render('Admin/Cars/Edit', [
            'car' => (new CarResource($car))->resolve(),
            'makers' => Maker::where('is_active', true)->get(['id', 'name']),
            'carModels' => CarModel::where('is_active', true)->get(['id', 'name', 'maker_id']),
            'fuels' => Fuel::where('is_active', true)->get(['id', 'name']),
            'colors' => Color::where('is_active', true)->get(['id', 'name']),
            'containers' => Container::get(['id', 'container_number']),
        ]);
    }

    // # Update specified resource
    public function update(UpdateCarRequest $request, Car $car): RedirectResponse
    {
        $this->carService->update($car, $request->validated());

        return redirect()->route('admin.cars.index')->with('success', 'Car updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(Car $car): RedirectResponse
    {
        $this->carService->delete($car);

        return back()->with('success', 'Car deleted successfully.');
    }
}
