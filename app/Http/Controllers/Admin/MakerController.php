<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Maker\StoreMakerRequest;
use App\Http\Requests\Admin\Maker\UpdateMakerRequest;
use App\Http\Resources\Admin\MakerResource;
use App\Models\Maker;
use App\Services\MakerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MakerController extends Controller
{
    public function __construct(
        private readonly MakerService $makerService,
    ) {}

    public function index(Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/Makers/Index', [
            'makers'  => MakerResource::collection(
                $this->makerService->getPaginatedWithSearch($search)
            ),
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Makers/Create');
    }

    public function store(StoreMakerRequest $request): RedirectResponse
    {
        $this->makerService->create($request->validated());
        return redirect()->route('admin.makers.index')->with('success', 'Maker created successfully.');
    }

    public function edit(Maker $maker): Response
    {
        return Inertia::render('Admin/Makers/Edit', [
            'maker' => new MakerResource($maker),
        ]);
    }

    public function update(UpdateMakerRequest $request, Maker $maker): RedirectResponse
    {
        $this->makerService->update($maker, $request->validated());
        return redirect()->route('admin.makers.index')->with('success', 'Maker updated successfully.');
    }

    public function destroy(Maker $maker): RedirectResponse
    {
        $this->makerService->delete($maker);
        return back()->with('success', 'Maker deleted successfully.');
    }
}
