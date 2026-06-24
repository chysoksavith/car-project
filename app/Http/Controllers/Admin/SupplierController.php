<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Supplier\StoreSupplierRequest;
use App\Http\Requests\Admin\Supplier\UpdateSupplierRequest;
use App\Http\Resources\Admin\SupplierResource;
use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SupplierController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly SupplierService $supplierService
    ) {
        $this->authorizeResource(Supplier::class, 'supplier');
    }

    // # Display listing of resource
    public function index(\Illuminate\Http\Request $request): Response
    {
        $search = $request->input('search');
        $suppliers = $this->supplierService->getPaginatedWithSearch($search, 10);

        return Inertia::render('Admin/Suppliers/Index', [
            'suppliers' => SupplierResource::collection($suppliers),
            'filters' => ['search' => $search],
        ]);
    }

    // # Show form for creating resource
    public function create(): Response
    {
        return Inertia::render('Admin/Suppliers/Create');
    }

    // # Store newly created resource
    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        $this->supplierService->createSupplier($request->validated());

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    // # Show form for editing resource
    public function edit(Supplier $supplier): Response
    {
        return Inertia::render('Admin/Suppliers/Edit', [
            'supplier' => new SupplierResource($supplier),
        ]);
    }

    // # Update specified resource
    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $this->supplierService->updateSupplier($supplier, $request->validated());

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $this->supplierService->deleteSupplier($supplier);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}
