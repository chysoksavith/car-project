<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Http\Requests\Admin\StoreContainerRequest;
use App\Http\Requests\Admin\UpdateContainerRequest;
use App\Http\Resources\Admin\ContainerResource;
use App\Services\ContainerService;
use App\Models\Maker;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Color;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContainerController extends Controller
{
    // # Initialize dependencies
    public function __construct(private readonly ContainerService $containerService)
    {
        $this->authorizeResource(Container::class, 'container');
    }

    // # Display listing of resource
    public function index(Request $request)
    {
        $query = Container::with(['cars.maker', 'cars.carModel', 'cars.color', 'cars.attachments']);

        if ($request->filled('search')) {
            $query->where('bl_number', 'like', "%{$request->search}%")
                  ->orWhere('container_number', 'like', "%{$request->search}%");
        }

        $containers = $query->with(['cars.maker', 'cars.carModel', 'cars.color', 'cars.attachments'])
                            ->orderByRaw('expected_date ASC NULLS LAST')
                            ->paginate(10)
                            ->withQueryString();

        return Inertia::render('Admin/Containers/Index', [
            'containers' => ContainerResource::collection($containers),
            'filters' => $request->only(['search']),
        ]);
    }

    // # Show form for creating resource
    public function create()
    {
        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Admin/Containers/Create', [
            'suppliers' => $suppliers,
            'makers' => Maker::where('is_active', true)->get(['id', 'name']),
            'carModels' => CarModel::where('is_active', true)->get(['id', 'name', 'maker_id']),
            'fuels' => Fuel::where('is_active', true)->get(['id', 'name']),
            'colors' => Color::where('is_active', true)->get(['id', 'name']),
        ]);
    }

    // # Store newly created resource
    public function store(StoreContainerRequest $request)
    {
        $this->containerService->createContainer($request->validated());

        return redirect()->route('admin.containers.index')->with('success', 'Container created successfully.');
    }

    // # Show form for editing resource
    public function edit(Container $container)
    {
        $container->load(['cars', 'cars.attachments', 'cars.color']);

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Admin/Containers/Edit', [
            'container' => new ContainerResource($container),
            'suppliers' => $suppliers,
            'makers' => Maker::where('is_active', true)->get(['id', 'name']),
            'carModels' => CarModel::where('is_active', true)->get(['id', 'name', 'maker_id']),
            'fuels' => Fuel::where('is_active', true)->get(['id', 'name']),
            'colors' => Color::where('is_active', true)->get(['id', 'name']),
        ]);
    }

    // # Update specified resource
    public function update(UpdateContainerRequest $request, Container $container)
    {
        $this->containerService->updateContainer($container, $request->validated());

        return redirect()->route('admin.containers.index')->with('success', 'Container updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(Container $container)
    {
        $this->containerService->deleteContainer($container);

        return redirect()->route('admin.containers.index')->with('success', 'Container deleted successfully.');
    }
}
