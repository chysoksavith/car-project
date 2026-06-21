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
    public function __construct(private readonly ShipmentService $shipmentService)
    {
    }

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

    public function create()
    {
        Gate::authorize('shipments.create');

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Admin/Shipments/Create', [
            'suppliers' => $suppliers,
        ]);
    }

    public function store(StoreShipmentRequest $request)
    {
        $this->shipmentService->createShipment($request->validated());

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment created successfully.');
    }

    public function edit(Shipment $shipment)
    {
        Gate::authorize('shipments.edit');

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Admin/Shipments/Edit', [
            'shipment' => new ShipmentResource($shipment),
            'suppliers' => $suppliers,
        ]);
    }

    public function update(UpdateShipmentRequest $request, Shipment $shipment)
    {
        $this->shipmentService->updateShipment($shipment, $request->validated());

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment updated successfully.');
    }

    public function destroy(Shipment $shipment)
    {
        Gate::authorize('shipments.delete');

        $this->shipmentService->deleteShipment($shipment);

        return redirect()->route('admin.shipments.index')->with('success', 'Shipment deleted successfully.');
    }
}
