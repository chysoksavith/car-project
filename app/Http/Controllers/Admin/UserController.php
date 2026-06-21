<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function index(Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/Users/Index', [
            'users'   => UserResource::collection(
                $this->userService->getPaginatedWithSearch($search)
            ),
            'filters' => ['search' => $search],
        ]);
    }
}
