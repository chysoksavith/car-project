<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Http\Resources\Admin\RoleResource;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly RoleService       $roleService,
        private readonly PermissionService $permissionService,
    ) {
        $this->authorizeResource(Role::class, 'role');
    }

    // # Display listing of resource
    public function index(\Illuminate\Http\Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/Roles/Index', [
            'roles'       => RoleResource::collection(
                $this->roleService->getPaginatedWithSearch($search)
            ),
            'permissions' => PermissionResource::collection($this->permissionService->all())->resolve(),
            'filters'     => ['search' => $search],
        ]);
    }

    // # Store newly created resource
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = $this->roleService->create(
            name: $request->validated('name'),
            permissions: $request->validated('permissions', []),
        );

        return back()->with('success', "Role '{$role->name}' created.");
    }

    // # Update specified resource
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $updated = $this->roleService->update(
            role: $role,
            name: $request->validated('name'),
            permissions: $request->validated('permissions', []),
        );

        return back()->with('success', "Role '{$updated->name}' updated.");
    }

    // # Remove specified resource from storage
    public function destroy(Role $role): RedirectResponse
    {
        $name = $role->name;
        $this->roleService->delete($role);

        return back()->with('success', "Role '{$name}' deleted.");
    }
}
