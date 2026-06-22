<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarModel\StoreCarModelRequest;
use App\Http\Requests\Admin\CarModel\UpdateCarModelRequest;
use App\Http\Resources\Admin\CarModelResource;
use App\Models\CarModel;
use App\Models\Maker;
use App\Services\CarModelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CarModelController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly CarModelService $carModelService,
    ) {}

    // # Display listing of resource
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/CarModels/Index', [
            'carModels' => CarModelResource::collection(
                $this->carModelService->getPaginatedWithSearch($search)
            ),
            'filters'   => ['search' => $search],
        ]);
    }

    // # Show form for creating resource
    public function create(): Response
    {
        return Inertia::render('Admin/CarModels/Create', [
            'makers' => Maker::all(),
        ]);
    }

    // # Store newly created resource
    public function store(StoreCarModelRequest $request): RedirectResponse
    {
        $this->carModelService->create($request->validated());
        return redirect()->route('admin.car-models.index')->with('success', 'Car Model created successfully.');
    }

    // # Show form for editing resource
    public function edit(CarModel $carModel): Response
    {
        return Inertia::render('Admin/CarModels/Edit', [
            'carModel' => new CarModelResource($carModel->load('maker')),
            'makers'   => Maker::all(),
        ]);
    }

    // # Update specified resource
    public function update(UpdateCarModelRequest $request, CarModel $carModel): RedirectResponse
    {
        $this->carModelService->update($carModel, $request->validated());
        return redirect()->route('admin.car-models.index')->with('success', 'Car Model updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(CarModel $carModel): RedirectResponse
    {
        $this->carModelService->delete($carModel);
        return back()->with('success', 'Car Model deleted successfully.');
    }
}
