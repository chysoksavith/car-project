<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly UserService $userService,
    ) {}

    // # Display listing of resource
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/Users/Index', [
            'users'   => \App\Http\Resources\Admin\UserResource::collection(
                $this->userService->getPaginatedWithSearch($search)
            ),
            'filters' => ['search' => $search],
        ]);
    }

    // # Show form for creating resource
    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create', [
            'roles'       => Role::all(),
            'companies'   => \App\Models\Company::all(),
            'departments' => \App\Models\Department::all(),
        ]);
    }

    // # Store newly created resource
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    // # Show form for editing resource
    public function edit(User $user): Response
    {
        $user->load(['roles', 'addresses']); // Ensure roles and addresses are loaded
        return Inertia::render('Admin/Users/Edit', [
            'user'        => $user,
            'roles'       => Role::all(),
            'companies'   => \App\Models\Company::all(),
            'departments' => \App\Models\Department::all(),
        ]);
    }

    // # Update specified resource
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userService->update($user, $request->validated());

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(User $user): RedirectResponse
    {
        $this->userService->delete($user);

        return back()->with('success', 'User deleted successfully.');
    }
}
