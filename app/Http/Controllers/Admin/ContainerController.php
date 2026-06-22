<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Http\Requests\Admin\StoreContainerRequest;
use App\Http\Requests\Admin\UpdateContainerRequest;
use App\Http\Resources\Admin\ContainerResource;
use App\Services\ContainerService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContainerController extends Controller
{
    // # Initialize dependencies
    public function __construct(private readonly ContainerService $containerService)
    {
    }

    // # Display listing of resource
    public function index(Request $request)
    {
        Gate::authorize('containers.view');

        $query = Container::query();

        if ($request->filled('search')) {
            $query->where('bl_number', 'like', "%{$request->search}%")
                  ->orWhere('container_number', 'like', "%{$request->search}%");
        }

        $containers = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Containers/Index', [
            'containers' => ContainerResource::collection($containers),
            'filters' => $request->only(['search']),
        ]);
    }

    // # Show form for creating resource
    public function create()
    {
        Gate::authorize('containers.create');

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Admin/Containers/Create', [
            'suppliers' => $suppliers,
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
        Gate::authorize('containers.edit');

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Admin/Containers/Edit', [
            'container' => new ContainerResource($container),
            'suppliers' => $suppliers,
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
        Gate::authorize('containers.delete');

        $this->containerService->deleteContainer($container);

        return redirect()->route('admin.containers.index')->with('success', 'Container deleted successfully.');
    }
}
