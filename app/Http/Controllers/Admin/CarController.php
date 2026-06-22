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
use Inertia\Inertia;
use Inertia\Response;

class CarController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly CarService $carService,
    ) {}

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
        $companyId = auth()->user()->company_id;

        return Inertia::render('Admin/Cars/Create', [
            'makers' => Maker::where('company_id', $companyId)->where('is_active', true)->get(['id', 'name']),
            'carModels' => CarModel::where('company_id', $companyId)->where('is_active', true)->get(['id', 'name', 'maker_id']),
            'fuels' => Fuel::where('company_id', $companyId)->where('is_active', true)->get(['id', 'name']),
            'colors' => Color::where('company_id', $companyId)->where('is_active', true)->get(['id', 'name']),
            'containers' => Container::where('company_id', $companyId)->get(['id', 'container_number']),
        ]);
    }

    // # Store newly created resource
    public function store(StoreCarRequest $request): RedirectResponse
    {
        $this->carService->create($request->validated());

        return redirect()->route('admin.cars.index')->with('success', 'Car created successfully.');
    }

    // # Show form for editing resource
    public function edit(Car $car): Response
    {
        // Ensure user can only edit cars in their company
        if ($car->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $car->load('attachments');

        $companyId = auth()->user()->company_id;

        return Inertia::render('Admin/Cars/Edit', [
            'car' => (new CarResource($car))->resolve(),
            'makers' => Maker::where('company_id', $companyId)->where('is_active', true)->get(['id', 'name']),
            'carModels' => CarModel::where('company_id', $companyId)->where('is_active', true)->get(['id', 'name', 'maker_id']),
            'fuels' => Fuel::where('company_id', $companyId)->where('is_active', true)->get(['id', 'name']),
            'colors' => Color::where('company_id', $companyId)->where('is_active', true)->get(['id', 'name']),
            'containers' => Container::where('company_id', $companyId)->get(['id', 'container_number']),
        ]);
    }

    // # Update specified resource
    public function update(UpdateCarRequest $request, Car $car): RedirectResponse
    {
        if ($car->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $this->carService->update($car, $request->validated());

        return redirect()->route('admin.cars.index')->with('success', 'Car updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(Car $car): RedirectResponse
    {
        if ($car->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $this->carService->delete($car);

        return back()->with('success', 'Car deleted successfully.');
    }
}
