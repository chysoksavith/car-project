<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Http\Requests\Admin\StoreShipmentRequest;
use App\Http\Requests\Admin\UpdateShipmentRequest;
use App\Http\Resources\Admin\ShipmentResource;
use App\Services\ShipmentService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ShipmentController extends Controller
{
    // # Initialize dependencies
    public function __construct(private readonly ShipmentService $shipmentService)
    {
    }

    // # Display listing of resource
    public function index(Request $request)
    {
        Gate::authorize('shipments.view');

        $query = Shipment::query();

        if ($request->filled('search')) {
            $query->where('bl_number', 'like', "%{$request->search}%")
                  ->orWhere('container_number', 'like', "%{$request->search}%");
        }

        $shipments = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Shipments/Index', [
            'shipments' => ShipmentResource::collection($shipments),
            'filters' => $request->only(['search']),
        ]);
    }

    // # Show form for creating resource
    public function create()
    {
        Gate::authorize('shipments.create');

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Admin/Shipments/Create', [
            'suppliers' => $suppliers,
        ]);
    }

    // # Store newly created resource
    public function store(StoreShipmentRequest $request)
    {
        $this->shipmentService->createShipment($request->validated());

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment created successfully.');
    }

    // # Show form for editing resource
    public function edit(Shipment $shipment)
    {
        Gate::authorize('shipments.edit');

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Admin/Shipments/Edit', [
            'shipment' => new ShipmentResource($shipment),
            'suppliers' => $suppliers,
        ]);
    }

    // # Update specified resource
    public function update(UpdateShipmentRequest $request, Shipment $shipment)
    {
        $this->shipmentService->updateShipment($shipment, $request->validated());

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(Shipment $shipment)
    {
        Gate::authorize('shipments.delete');

        $this->shipmentService->deleteShipment($shipment);

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment deleted successfully.');
    }
}
