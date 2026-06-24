<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Admin\Company\StoreCompanyRequest;
use App\Http\Requests\Admin\Company\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    // # Initialize dependencies
    public function __construct(
        private readonly CompanyService $companyService,
    ) {
        $this->authorizeResource(Company::class, 'company');
    }

    // # Display listing of resource
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        return Inertia::render('Admin/Companies/Index', [
            'companies' => \App\Http\Resources\Admin\CompanyResource::collection(
                $this->companyService->getPaginatedWithSearch($search)
            ),
            'filters'   => ['search' => $search],
        ]);
    }

    // # Show form for creating resource
    public function create(): Response
    {
        return Inertia::render('Admin/Companies/Create');
    }

    // # Store newly created resource
    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $this->companyService->create($request->validated());

        return redirect()->route('admin.companies.index')->with('success', 'Company created successfully.');
    }

    // # Show form for editing resource
    public function edit(Company $company): Response
    {
        return Inertia::render('Admin/Companies/Edit', [
            'company' => new \App\Http\Resources\Admin\CompanyResource($company),
        ]);
    }

    // # Update specified resource
    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $this->companyService->update($company, $request->validated());

        return redirect()->route('admin.companies.index')->with('success', 'Company updated successfully.');
    }

    // # Remove specified resource from storage
    public function destroy(Company $company): RedirectResponse
    {
        $this->companyService->delete($company);

        return back()->with('success', 'Company deleted successfully.');
    }
}
