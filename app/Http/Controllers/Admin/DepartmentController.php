<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Department\StoreDepartmentRequest;
use App\Http\Requests\Admin\Department\UpdateDepartmentRequest;
use App\Http\Resources\Admin\DepartmentResource;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly DepartmentService $departmentService,
    ) {
        $this->authorizeResource(Department::class, 'department');
    }

    // # Display listing of resource
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/Departments/Index', [
            'departments'  => DepartmentResource::collection(
                $this->departmentService->getPaginatedWithSearch($search)
            ),
            'filters' => ['search' => $search],
        ]);
    }

    // # Show form for creating resource
    public function create(): Response
    {
        return Inertia::render('Admin/Departments/Create');
    }

    // # Store newly created resource
    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $this->departmentService->create($request->validated());
        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    // # Show form for editing resource
    public function edit(Department $department): Response
    {
        return Inertia::render('Admin/Departments/Edit', [
            'department' => new DepartmentResource($department),
        ]);
    }

    // # Update specified resource
    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $this->departmentService->update($department, $request->validated());
        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(Department $department): RedirectResponse
    {
        $this->departmentService->delete($department);
        return back()->with('success', 'Department deleted successfully.');
    }
}
