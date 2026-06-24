<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InspectionItem\StoreInspectionItemRequest;
use App\Http\Requests\Admin\InspectionItem\UpdateInspectionItemRequest;
use App\Http\Resources\Admin\InspectionItemResource;
use App\Models\InspectionItem;
use App\Services\InspectionItemService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InspectionItemController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly InspectionItemService $inspectionItemService
    ) {
        $this->authorizeResource(InspectionItem::class, 'inspection_item');
    }

    // # Display listing of resource
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $items = $this->inspectionItemService->getPaginatedWithSearch($search, 10);

        return Inertia::render('Admin/InspectionItems/Index', [
            'inspectionItems' => InspectionItemResource::collection($items),
            'filters' => ['search' => $search],
        ]);
    }

    // # Show form for creating resource
    public function create(): Response
    {
        $categories = $this->inspectionItemService->getMainCategories();

        return Inertia::render('Admin/InspectionItems/Create', [
            'categories' => InspectionItemResource::collection($categories),
        ]);
    }

    // # Store newly created resource
    public function store(StoreInspectionItemRequest $request): RedirectResponse
    {
        $this->inspectionItemService->createItem($request->validated());

        return redirect()->route('admin.inspection-items.index')
            ->with('success', 'Inspection item created successfully.');
    }

    // # Show form for editing resource
    public function edit(InspectionItem $inspectionItem): Response
    {
        $categories = $this->inspectionItemService->getMainCategories();

        return Inertia::render('Admin/InspectionItems/Edit', [
            'inspectionItem' => new InspectionItemResource($inspectionItem),
            'categories'     => InspectionItemResource::collection($categories),
        ]);
    }

    // # Update specified resource
    public function update(UpdateInspectionItemRequest $request, InspectionItem $inspectionItem): RedirectResponse
    {
        $this->inspectionItemService->updateItem($inspectionItem, $request->validated());

        return redirect()->route('admin.inspection-items.index')
            ->with('success', 'Inspection item updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(InspectionItem $inspectionItem): RedirectResponse
    {
        try {
            $this->inspectionItemService->deleteItem($inspectionItem);

            return redirect()->route('admin.inspection-items.index')
                ->with('success', 'Inspection item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.inspection-items.index')
                ->with('error', $e->getMessage());
        }
    }
}
