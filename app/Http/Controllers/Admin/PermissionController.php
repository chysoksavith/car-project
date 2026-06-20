<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\StorePermissionRequest;
use App\Http\Requests\Admin\Permission\UpdatePermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Services\PermissionService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionService $permissionService,
    ) {}

    // ─────────────────────────────────────────────────────────────────────────

    public function index(\Illuminate\Http\Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/Permissions/Index', [
            'permissions' => PermissionResource::collection(
                $this->permissionService->getPaginatedWithSearch($search)
            ),
            'filters' => ['search' => $search],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        $permission = $this->permissionService->create($request->validated('name'));

        return back()->with('success', "Permission '{$permission->name}' created.");
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $updated = $this->permissionService->update($permission, $request->validated('name'));

        return back()->with('success', "Permission renamed to '{$updated->name}'.");
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function destroy(Permission $permission): RedirectResponse
    {
        $name = $permission->name;
        $this->permissionService->delete($permission);

        return back()->with('success', "Permission '{$name}' deleted.");
    }
}
