<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarInspection\UpdateCarInspectionRequest;
use App\Http\Resources\Admin\CarInspectionResource;
use App\Http\Resources\Admin\CarResource;
use App\Http\Resources\Admin\InspectionItemResource;
use App\Models\Car;
use App\Models\CarInspection;
use App\Services\CarInspectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CarInspectionController extends Controller
{
    public function __construct(
        private readonly CarInspectionService $carInspectionService
    ) {}

    // # Display car selection and inspection list
    public function index(Request $request): Response
    {
        $carId = $request->input('car_id');
        $cars = $this->carInspectionService->getCarsForInspection();

        $selectedCar = null;
        $inspections = collect();

        if ($carId) {
            $selectedCar = Car::with(['maker', 'carModel', 'color', 'fuel', 'attachments'])->find($carId);

            if ($selectedCar) {
                // Initialize inspections if none exist
                $existingInspections = $this->carInspectionService->getCarInspections($carId);
                if ($existingInspections->isEmpty()) {
                    $inspections = $this->carInspectionService->initializeCarInspections($carId);
                } else {
                    $inspections = $existingInspections;
                }

                // Get grouped inspections
                $groupedInspections = $this->carInspectionService->getGroupedCarInspections($carId);
            }
        }

        return Inertia::render('Admin/CarInspections/Index', [
            'cars' => CarResource::collection($cars)->resolve(),
            'selectedCar' => $selectedCar ? (new CarResource($selectedCar))->resolve() : null,
            'inspections' => CarInspectionResource::collection($inspections)->resolve(),
            'groupedInspections' => $groupedInspections ?? [],
            'selectedCarId' => $carId,
        ]);
    }

    // # Update inspection cost (AJAX)
    public function updateCost(UpdateCarInspectionRequest $request, CarInspection $carInspection): JsonResponse
    {
        $this->carInspectionService->updateInspectionCost(
            $carInspection,
            $request->input('cost', 0)
        );

        return response()->json([
            'success' => true,
            'cost' => $carInspection->fresh()->cost,
        ]);
    }

    // # Update inspection details (condition and note)
    public function updateDetails(UpdateCarInspectionRequest $request, CarInspection $carInspection): JsonResponse
    {
        $this->carInspectionService->updateInspectionDetails(
            $carInspection,
            $request->only(['condition', 'note'])
        );

        return response()->json([
            'success' => true,
        ]);
    }

    // # Re-initialize inspections for a car
    public function initialize(Request $request): RedirectResponse
    {
        $carId = $request->input('car_id');

        if ($carId) {
            $this->carInspectionService->initializeCarInspections($carId);
        }

        return redirect()->route('admin.car-inspections.index', ['car_id' => $carId])
            ->with('success', 'Inspections initialized successfully.');
    }
}
