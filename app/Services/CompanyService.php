<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    /**
     * Get paginated companies with optional search filter.
     */
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 15)
    {
        return Company::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(Company $company, array $data): Company
    {
        $company->update($data);

        return $company;
    }

    public function delete(Company $company): bool
    {
        return $company->delete();
    }
}
